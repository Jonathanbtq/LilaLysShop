<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\PanierProduit;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartRepository $cartRepo): Response
    {
        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        $products = $cart->getPanierProduits();
        $prdArray = [];
        foreach($products as $prd){
            $prdArray[] = $prd;
        }
        return $this->render('cart/index.html.twig', [
            'cart' =>  $cart,
            'products' => $prdArray
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
        $cart->setNbProducts($cart->getNbProducts()+1);
        $cart->setTotalPrice($cart->getTotalPrice()+$product->getPrice());
        $cartRepo->save($cart, true);

        // Création de la ligne produit
        $ligneProduit = new PanierProduit();
        $ligneProduit->setCart($cart);
        if($this->getUser()){
            $ligneProduit->setIdClient($this->getUser());
        }
        $ligneProduit->setIdProduit($product);
        $ligneProduit->setPrice($product->getPrice());
        $panierProduitRepo->save($ligneProduit, true);
        // $panierProduitRepo->persist($ligneProduit);
        // $panierProduitRepo->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/get_item_count', name: "get_item_count")]
    public function getCartItemCount(CartRepository $cartRepo, PanierProduitRepository $panierRepo): JsonResponse
    {
        $panierCount = $panierRepo->findBy(['id_client' => $this->getUser()]);
        $nbCount = count($panierCount);

        $cart = $cartRepo->findOneBy(['user' => $this->getUser()]);
        if($nbCount != null){
            $cart->setNbProducts($nbCount);
        }
        $cartRepo->save($cart, true);
        $cartItemCount = $cart->getNbProducts();
        return $this->json(['count' => $cartItemCount]);
    }

    // Ajouter un effet d'attente sur le site pour le temps de réponse du serveur
    #[Route('/get_pricecart_count', name: "get_pricecart_count")]
    public function getCartPrice(CartRepository $cartRepo, PanierProduitRepository $panierRepo): JsonResponse
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
}
