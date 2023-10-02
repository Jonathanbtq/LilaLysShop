<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\CodePromo;
use App\Entity\PanierProduit;
use App\Form\CodePromoFormType;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Form\CodePromoFormFindType;
use App\Repository\ProductRepository;
use App\Repository\CodePromoRepository;
use App\Repository\PanierProduitRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartRepository $cartRepo, Request $request, CodePromoRepository $codePromoRepo): Response
    {
        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        $products = $cart->getPanierProduits();
        $prdArray = [];
        foreach($products as $prd){
            $prdArray[] = $prd;
        }

        // Code promo formulaire

        $formCode = $this->createForm(CodePromoFormFindType::class);
        $formCode->handleRequest($request);
        $reductionPromo = ['value' => '', 'data' => ''];
        $previousTotal = $cart->getTotalPrice();

        if($formCode->isSubmitted() && $formCode->isValid()){
            $PromoCode = $codePromoRepo->findOneBy(['code' => $formCode->get('code')->getData()]);
            $cart->setCodepromo($PromoCode);
            $cartRepo->save($cart, true);
            
            if($PromoCode && new \DateTime() < $PromoCode->getExpirationDate() ){
                $reductionPromo = ['value' => $PromoCode->getPourcentageReduction(),
                'data' => 'int'
                ];
                $previousTotal = $cart->getTotalPrice();
                $cart->setTotalPrice($cart->getTotalPrice() - $PromoCode->getPourcentageReduction());
            }else{
                $reductionPromo = ['value' => 'Vous n\'avez pas de code promo valide',
                'data' => 'txt'
            ];
            }
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' =>  $cart,
            'previousTotal' => $previousTotal,
            'products' => $prdArray,
            'recution_promo' => $reductionPromo,
            'formcode' => $formCode
        ]);
    }

    #[Route('/add-to-cart/{productId}', name: "add-to-cart")]
    public function addToCart(int $productId, ProductRepository $productRepo, CartRepository $cartRepo, PanierProduitRepository $panierProduitRepo): JsonResponse
    {
        // Calcul du nombre d'article
        // Cherche le carte existant et on récupere le nb d'article deja présent
        $product = $productRepo->findOneBy(['id' => $productId]);
        if($this->getUser()){
            $user = $this->getUser();
            $cartExist = $cartRepo->findOneBy(['user' => $user]);
        }
        // Création du Cart et panierProduit
        if($cartExist){
            $cart = $cartExist;
        }else{
            $cart = new Cart();
            $cart->setDescription('Panier temporaire (10 jours), pour ne pas le perdre, veuillez créer un compte.');
        }
        if($this->getUser()){
            $cart->setUser($this->getUser());
        }
        $nbProduitCart = $cart->getNbProducts();
        $cart->setNbProducts($nbProduitCart + 1);
        $cart->setTotalPrice($cart->getTotalPrice()+$product->getPrice());
        $cartRepo->save($cart, true);

        // Création de la ligne produit
        $panierExisting = $panierProduitRepo->findBy(['id_client' => $this->getUser()]);
        if($panierExisting){
            foreach($panierExisting as $prdExisting){
                if($prdExisting->getIdProduit()->getId() == $product->getId()){
                    $nbProduct = $prdExisting->getNbProduct();
                    $prdExisting->setNbProduct($nbProduct + 1);
                    $prdExisting->setPrice($prdExisting->getPrice() * $prdExisting->getNbProduct());
                    $panierProduitRepo->save($prdExisting, true);
                }
            }
        }else{
            $ligneProduit = new PanierProduit();
            $ligneProduit->setCart($cart);
            if($this->getUser()){
                $ligneProduit->setIdClient($this->getUser());
            }
            $ligneProduit->setIdProduit($product);
            $ligneProduit->setPrice($product->getPrice());
            $ligneProduit->setNbProduct(1);
            $panierProduitRepo->save($ligneProduit, true);
        }
        
        // $panierProduitRepo->persist($ligneProduit);
        // $panierProduitRepo->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/get_item_count', name: "get_item_count")]
    public function getCartItemCount(CartRepository $cartRepo, PanierProduitRepository $panierRepo): JsonResponse
    {
        $panierCount = $panierRepo->findBy(['id_client' => $this->getUser()]);
        $nbCount = 0;
        foreach($panierCount as $nbproduct){
            $nbCount = $nbCount + $nbproduct->getNbProduct();
        }

        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        if($nbCount != null){
            $cart->setNbProducts($nbCount);
        }
        $cartRepo->save($cart, true);
        $cartItemCount = $cart->getNbProducts();
        $cartPrice = $this->getCartPrice($cartRepo);
        return $this->json(['count' => $cartItemCount, 'cartprice' => $cartPrice]);
    }

    // Ajouter un effet d'attente sur le site pour le temps de réponse du serveur
    #[Route('/get_pricecart_count', name: "get_pricecart_count")]
    public function getCartPrice(CartRepository $cartRepo): JsonResponse
    {
        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        $cartPrice = $cart->getTotalPrice();

        return $this->json(['totalPrice' => $cartPrice]);
    }

    #[Route('/get_item_delete/{id}', name: "get_item_delete")]
    public function getCartItemDelete($id, PanierProduitRepository $panierRepo, CartRepository $cartRepo, ProductRepository $productRepo): JsonResponse
    {
        $panierCount = $panierRepo->findOneBy(['id_produit' => $id]);
        $panierRepo->remove($panierCount, true);

        $produit = $productRepo->findOneBy(['id' => $id]);

        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        $cart->setTotalPrice($cart->getTotalPrice() - $produit->getPrice());
        $cartRepo->save($cart, true);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/checkoutcart/{cartid}', name: 'paiment_cart')]
    public function paimentPage($cartid, CartRepository $cartRepo, Request $request, CodePromoRepository $codePromoRepo): Response
    {
        $cart = $cartRepo->findOneBy(['id' => $cartid]);
        if($cart->getCodepromo() != null){
            $PromoCode = $cart->getCodepromo();
        }
        
        return $this->render('cart/paiementcart.html.twig', [
            'cart' =>  $cart,
            'recution_promo' => $PromoCode,
        ]);
    }
}
