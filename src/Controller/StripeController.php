<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/checkout/{cart}', name: 'stripe_checkout')]
    public function checkout($cart, $stripeSK, CartRepository $cartRepo, ProductRepository $productRepo): Response
    {
        $cart = $cartRepo->findOneBy(['id' => $cart]);

        if($cart->getCodepromo() != null){
            $reduction = $cart->getCodepromo()->getPourcentageReduction();
        }
        foreach($cart->getPanierProduits() as $panier){
            $product = $panier->getIdProduit();
            $prd = $productRepo->findOneBy(['id' => $product]);

            $redPrice = ($prd->getPrice()*$reduction) / 100;
            $price = $prd->getPrice() - $redPrice;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $prd->getName(),
                    ],
                    'unit_amount' => $price * 100,
                ],
                'quantity' => $panier->getNbProduct(), // Vous pouvez ajuster la quantité en fonction de votre modèle de données
            ];
        }

        Stripe::setApiKey($stripeSK);

        try {
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

        } catch (\Exception $e) {
            // Gérer les erreurs ici
            dd($e);
            return $e;
        }

        return $this->redirect($checkoutSession->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(UserRepository $userRepo): Response
    {
        return $this->render('stripe/success.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('stripe/cancel.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }
}
