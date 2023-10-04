<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use Stripe\Checkout\Session;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/checkout/{cart}', name: 'stripe_checkout')]
    public function checkout($cart, $stripeSK, CartRepository $cartRepo, ProductRepository $productRepo): Response
    {
        $cart = $cartRepo->findOneBy(['id' => $cart]);

        $reduction = null;
        if($cart->getCodepromo() != null){
            $reduction = $cart->getCodepromo()->getPourcentageReduction();
            $linePromo = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Code promo ' . $cart->getCodepromo()->getCode(),
                    ],
                    'unit_amount' => 0,
                ],
                'quantity' => 1,
            ];
        }
        foreach($cart->getPanierProduits() as $panier){
            $product = $panier->getIdProduit();
            $prd = $productRepo->findOneBy(['id' => $product]);

            if($reduction != null){
                $redPrice = ($prd->getPrice()*$reduction) / 100;
                $price = $prd->getPrice() - $redPrice;
            }else{
                $price = $prd->getPrice();
            }
            
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
        array_push($lineItems, $linePromo);

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
    public function successUrl(UserRepository $userRepo, OrderRepository $orderRepo, CartRepository $cartRepo): Response
    {
        $order = new Order();
        $user = $this->getUser();
        $cart = $cartRepo->findOneBy(['user' => $user]);

        $totalReduction = $cart->getTotalPrice() * $cart->getCodepromo()->getPourcentageReduction() / 100;
        $adresseId = $cart->getUser()->getAdresse();
        $adresse = $adresseId->getRue() . ' ' . $adresseId->getCodePostal() . ' ' . $adresseId->getCity()->getLabel() . ' ' . $adresseId->getCodePostal() . ' ' . $adresseId->getPays()->getNom();
        
        $order->setUser($this->getUser());
        $order->setClientName($cart->getUser()->getName());
        $order->setAdresseFact($adresse);
        $order->setTelephone($cart->getUser()->getTelephone());
        $order->setFactMail($cart->getUser()->getEmail());
        $order->setWorkModele('B2B');
        $order->setDescription('Commande Validée');
        if($cart->getCodepromo() != null){
            $order->setIsPromo(1);
        }else{
            $order->setIsPromo(0);
        }
        $order->setTotalDiscount($cart->getCodepromo()->getPourcentageReduction());
        $order->setNbProduct($cart->getNbProducts());
        $order->setShipPrice(0);
        $order->setOrderDate(new \DateTime());
        $order->setDateBonCommande(new \DateTime());
        $order->setPriceBfrTaxe($cart->getTotalPrice());
        $order->setSousTotal($cart->getTotalPrice() - $totalReduction);
        $order->setSousTotalTaxe($totalReduction);
        // Ajouter TVA au prix total
        $order->setTotalPrice($cart->getTotalPrice() - $totalReduction);
        $order->setTypeTaxeLocal('TVA');
        $order->setIsValid(0);

        $orderRepo->save($order, true);
        // $cartRepo->remove($cart, true);

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
