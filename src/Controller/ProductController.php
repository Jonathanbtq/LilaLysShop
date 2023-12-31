<?php

namespace App\Controller;

use App\Entity\PanierProduit;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('shop/product/{idproduit}', name: 'product')]
    public function index($idproduit, ProductRepository $produitRepo): Response
    {
        $product = $produitRepo->findOneBy(['id' => $idproduit]);

        return $this->render('product/product_shop.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('shop/products', name: 'products')]
    public function showProduct(ProductRepository $produitRepo): Response
    {
        $products = $produitRepo->findBy(['product_type' => 'Product']);

        return $this->render('product/products.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('shop/pattern', name: 'pattern')]
    public function showPattern(ProductRepository $produitRepo): Response
    {
        $products = $produitRepo->findBy(['product_type' => 'Pattern']);

        return $this->render('product/pattern.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('shop/freestuff', name: 'freestuff')]
    public function showFreeStuff(ProductRepository $produitRepo): Response
    {
        $products = $produitRepo->findBy(['product_type' => 'FreeStuff']);

        return $this->render('product/freestuff.html.twig', [
            'products' => $products
        ]);
    }
}
