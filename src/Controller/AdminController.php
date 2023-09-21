<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductAddTypeFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'adminindex')]
    public function index(ProductRepository $produitRepo): Response
    {
        $produits = $produitRepo->findBy([], null, 5);
        return $this->render('admin/index.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/newproduct', name: 'adminnewproduct')]
    public function newProduct(EntityManagerInterface $entityManager, ProductRepository $produitRepo, Request $request): Response
    {
        $newProduit = New Product();
        $form = $this->createForm(ProductAddTypeFormType::class, $newProduit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            // $produitRepo->save($newProduit, true);
            $entityManager->persist($newProduit);
            $entityManager->flush();
            return $this->redirectToRoute('admin_adminindex');
        }
        return $this->render('admin/newproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
