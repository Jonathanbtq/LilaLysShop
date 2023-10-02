<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaypalController extends AbstractController
{
    #[Route('/paypal/{idCart}', name: 'paypal')]
    public function index($idCart, $paypalSK, CartRepository $cartRepo): Response
    {
        $paypalKey = $paypalSK;
        $cart = $cartRepo->findOneBy(['id' => $idCart]);
        if(!$cart){
            return $this->redirectToRoute('cart');
        }

        $items = [];
        
        $panniers = $cart->getPanierProduits();
        foreach($panniers as $pannier){
            $idProduct = $pannier->getIdProduit();
            dd($idProduct->getName());
            $items[] = [
                'name' => $idProduct->getName(),
                'quantity' => 1,
                'unit_amount' => [
                    'value' => $idProduct->getPrice(),
                    'currency_code' => 'EUR'
                ]
            ];
            // $itemTotalPrice = $idProduct->getPrice() * $pannier->getNbProduct();
        }
        return $this->render('paypal/paypal.html.twig', [
            'controller_name' => 'PaypalController',
        ]);
    }

    #[Route('/paypal_success', name: 'paypal_success')]
    public function paypalSuccess(): Response
    {
        return $this->render('paypal/paypal.html.twig', [
            'controller_name' => 'PaypalController',
        ]);
    }

    #[Route('/paypal_cancel', name: 'paypal_cancel')]
    public function paypalCancel(): Response
    {
        return $this->render('paypal/paypal.html.twig', [
            'controller_name' => 'PaypalController',
        ]);
    }
}
