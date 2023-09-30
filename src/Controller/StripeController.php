<?php

namespace App\Controller;

use App\Repository\CartRepository;
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
    public function checkout($cart, $stripeSK, CartRepository $cartRepo): Response
    {
        $cart = $cartRepo->findOneBy(['id' => $cart]);
        Stripe::setApiKey($stripeSK);

        try {
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Crochet Moyen',
                        ],
                        'unit_amount' => 2000,
                    ],
                    'quantity' => 1,
                ]],
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
    public function successUrl($userId, UserRepository $userRepo): Response
    {
        $user = $userRepo->findOneBy(['id' => $userId]);
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
