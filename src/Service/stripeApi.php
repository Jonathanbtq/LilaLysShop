<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class stripeApi
{
    private $stripeSecretKey;

    public function __construct($stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
    }

    public function createCheckoutSession($priceId, $quantity, $STRIPE_SECRET_KEY)
    {
        Stripe::setApiKey($STRIPE_SECRET_KEY);

        $YOUR_DOMAIN = 'http://example.com'; // Mettez à jour avec votre domaine en direct

        try {
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => $quantity,
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]);

            return $checkoutSession->url;
        } catch (\Exception $e) {
            // Gérer les erreurs ici
            return null;
        }
    }
}