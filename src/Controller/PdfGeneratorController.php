<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfGeneratorController extends AbstractController
{
    #[Route('/viewpdf/{userid}/{orderid}', name: 'contrat_pdf_view')]
    public function index($userid, $orderid, UserRepository $userRepo, CartRepository $cartRepo, OrderRepository $orderRepo): Response
    {
        $user = $userRepo->findOneBy(['id' => $userid]);
        $order = $orderRepo->findOneBy(['id' => $orderid]);
        $projectDir = $this->getParameter('kernel.project_dir');

        $data = [
            // 'logoPoleFormation'  => $this->imageToBase64($projectDir . '/public/assets/images/LogoPoleFormation.JPG'),
            'today' => new \DateTime(),
            'user' => $user,
            'order' => $order,
            'root' => $projectDir
        ];
        $html =  $this->render('pdf_generator/indexpdf.html.twig', $data);
        
        $dompdf = new Dompdf(['chroot' => __DIR__]);
        $dompdf->loadHtml($html);
        $dompdf->render();
            
        return new Response (
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $user->getName() . '-' . $userid . '.pdf"',
            ]
        );
    }

    #[Route('/dlpdf/{userid}/{orderid}', name: 'contrat_pdf_dl')]
    public function pdfDownload($userid, $orderid, UserRepository $userRepo, CartRepository $cartRepo, OrderRepository $orderRepo): Response
    {
        $user = $userRepo->findOneBy(['id' => $userid]);
        $order = $orderRepo->findOneBy(['id' => $orderid]);
        $projectDir = $this->getParameter('kernel.project_dir');

        $data = [
            // 'logoPoleFormation'  => $this->imageToBase64($projectDir . '/public/assets/images/LogoPoleFormation.JPG'),
            'today' => new \DateTime(),
            'user' => $user,
            'order' => $order,
            'root' => $projectDir
        ];
        $html =  $this->render('pdf_generator/indexpdf.html.twig', $data);
        
        $dompdf = new Dompdf(['chroot' => __DIR__]);
        $dompdf->loadHtml($html);
        $dompdf->render();
            
        return new Response (
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $user->getName() . '-' . $userid . '.pdf"',
            ]
        );
    }

    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
