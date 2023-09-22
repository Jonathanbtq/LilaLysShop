<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ProductRepository $productRepo, CartRepository $cartRepo): Response
    {
        $product = $productRepo->findAll();
        $nbProduit = $cartRepo->findOneBy(['user' => $this->getUser()]);
        return $this->render('main/index.html.twig', [
            'products' => $product,
            'nbProduit' => $nbProduit
        ]);
    }
}