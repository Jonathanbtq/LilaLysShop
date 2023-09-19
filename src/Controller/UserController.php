<?php

namespace App\Controller;

use App\Form\AccountModifFormType;
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
    public function accountModif($email, UserRepository $userRepo, Request $request): Response
    {
        $user = $userRepo->findOneBy(['email' => $email]);
        
        $form = $this->createForm(AccountModifFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('user/accountmodif.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
