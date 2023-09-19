<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AccountModifFormType;
use App\Repository\AdresseRepository;
use App\Repository\PaysRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/useraccount/{email}', name: 'account')]
    public function index($email, UserRepository $userRepo): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);
        return $this->render('user/account.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/accountmodif/{email}', name: 'accountmodif')]
    public function accountModif($email, UserRepository $userRepo, AdresseRepository $addrRepo, PaysRepository $paysRepo, Request $request): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);
        
        $form = $this->createForm(AccountModifFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rue = $form['rue']->getData();
            $complement_adrr = $form['complement_adrr']->getData();
            $city = $form['city']->getData();
            $code_postal = $form['code_postal']->getData();
            $adresse = new Adresse();

            // Récupération du pays et de son id
            $pays = $form['pays']->getData();
            $pays = $paysRepo->findOneBy(['nom' => $pays]);

            $adresse->setCity($city);
            $adresse->setPays($pays->getId());
            $adresse->setRue($rue);
            $adresse->setComplementAdrr($complement_adrr);
            $adresse->setCodePostal($code_postal);
            $addrRepo->save($adresse, true);
        }
        return $this->render('user/accountmodif.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
