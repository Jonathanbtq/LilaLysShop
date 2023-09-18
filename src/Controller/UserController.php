<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
