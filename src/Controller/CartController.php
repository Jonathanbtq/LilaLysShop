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
        return $this->render('cart/index.html.twig', [
            'cart' =>  $cart
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

        $response = ['message' => 'Produit ajouté au panier avec succès'];

        return new JsonResponse($response);
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
}
