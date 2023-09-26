<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AccountModifFormType;
use App\Repository\AdresseRepository;
use App\Repository\CityRepository;
use App\Repository\OrderRepository;
use App\Repository\PaysRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/useraccount', name: 'account')]
    public function index(UserRepository $userRepo): Response
    {
        $user = $this->getUser();
        return $this->render('user/account.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/accountmodif/{email}', name: 'accountmodif')]
    public function accountModif($email, UserRepository $userRepo, CityRepository $cityRepo, AdresseRepository $addrRepo, PaysRepository $paysRepo, Request $request): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);

        $form = $this->createForm(AccountModifFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $cityName = $_POST['city'];
            $suggestions = [];
            $city = $cityRepo->findOneBy(['label' => $cityName]);
            if (!$city) {
                $cities = $cityRepo->findAll();
        
                foreach ($cities as $city) {
                    $distance = levenshtein($cityName, $city->getLabel());
        
                    // Vous pouvez ajuster la valeur de distance de Levenshtein en fonction de vos besoins
                    if ($distance <= 3) {
                        $suggestions[] = $city->getLabel();
                    }
                }
            }
            $adresse = new Adresse();
            $adresse->setCity($city);
            $addrRepo->save($adresse, true);

            if($form['email']->getData() == $user->getEmail()){
                $user->setEmail($user->getEmail());
            }
            $user->setAdresse($adresse->getId());
            $userRepo->save($user, true);

            return $this->redirectToRoute('account');
        }
        return $this->render('user/accountmodif.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/bon_commande/{email}', name: 'bon_commande')]
    public function BonCommande($email, UserRepository $userRepo, OrderRepository $orderRepo): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);
        $order = $orderRepo->findBy(['user' => $user]);

        return $this->render('user/boncommande.html.twig', [
            'user' => $user,
            'order' => $order
        ]);
    }

    #[Route('/facture/{email}', name: 'facture')]
    public function FactureCreate($email, UserRepository $userRepo, OrderRepository $orderRepo): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);
        $order = $orderRepo->findBy(['user' => $user]);

        foreach($order as $order){
            if($order->isIsValid()){
                $order = $order;
            }
        }
        return $this->render('user/facture.html.twig', [
            'user' => $user,
            'order' => $order
        ]);
    }
}
