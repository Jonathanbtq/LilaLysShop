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
}
