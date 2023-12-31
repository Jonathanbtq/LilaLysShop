<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Entity\Category;
use App\Entity\CodePromo;
use App\Entity\ProductImg;
use App\Form\CategoryFormType;
use App\Form\CodePromoFormType;
use App\Form\GetBonCommandeFormType;
use App\Form\ProductAddTypeFormType;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CodePromoRepository;
use App\Repository\OrderRepository;
use App\Repository\PanierProduitRepository;
use App\Repository\ProductImgRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'adminindex')]
    public function index(Request $request, PdfGeneratorController $pdfGenerator, UserRepository $userRepo, ProductRepository $produitRepo, CategoryRepository $categoryRepo, OrderRepository $orderRepo, CodePromoRepository $codePromoRepo): Response
    {
        $category = $categoryRepo->findAll();
        $produits = $produitRepo->findBy([], null, 10);
        $user = $userRepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]) ;

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $order->setUser($this->getUser());
            $order->setOrderDate(new \DateTime());
            $order->setDateBonCommande(new \DateTime());
            $order->setIsPromo(true);
            $order->setTypeTaxeLocal('15%');
            $order->setClientName($user->getName());
            $orderRepo->save($order, true);
            return $this->redirectToRoute('contrat_pdf_view', ['userid' => $user->getId(), 'orderid' => $order->getId()]);
        }

        // Récupération d'un bon de commande
        $formPdf = $this->createForm(GetBonCommandeFormType::class);
        $formPdf->handleRequest($request);

        if($formPdf->isSubmitted() && $formPdf->isValid()){
            $data = $formPdf->getData();
            $orderId = $data['orderId'];
            $action = $data['action'];

            if ($action === 'download') {
                // Redirige vers la route pour télécharger le PDF
                return $this->redirectToRoute('contrat_pdf_dl', ['userid' => $user->getId(), 'orderid' => $orderId]);
            } elseif ($action === 'view') {
                // Redirige vers la route pour afficher le PDF
                return $this->redirectToRoute('contrat_pdf_view', ['userid' => $user->getId(), 'orderid' => $orderId]);
            }
        }

        // Code promo formulaire

        $PromoCode = new CodePromo();
        $formCode = $this->createForm(CodePromoFormType::class, $PromoCode);
        $formCode->handleRequest($request);

        if($formCode->isSubmitted() && $formCode->isValid()){
            $codePromoRepo->save($PromoCode, true);
        }
        
        return $this->render('admin/index.html.twig', [
            'produits' => $produits,
            'category' => $category,
            'form_order' => $form,
            'form_bdc' => $formPdf,
            'form_promo' => $formCode
        ]);
    }

    /**
     * Ajout de produits
     */
    #[Route('/newproduct', name: 'adminnewproduct')]
    public function newProduct(EntityManagerInterface $entityManager, ProductImgRepository $productImgRepo, ProductRepository $produitRepo, #[Autowire('%product_photo_dir%')] string $photoDir, Request $request): Response
    {
        $newProduit = New Product();
        $form = $this->createForm(ProductAddTypeFormType::class, $newProduit);
        $form->handleRequest($request);
        
        $directory = $photoDir.'/'.$newProduit->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepo->save($newProduit, true);

            $img = $form['product_image']->getData();
            if($img){
                $filename = $this->createFolderImg($photoDir, $img, $newProduit);
                $newProduit->setProductImage($filename);
            }
            if($multipleImg = $form['productImgs']->getData()){
                $filenames = $this->createFolderImgs($photoDir, $multipleImg, $newProduit);
                foreach($filenames as $filename){
                    $prdImg = new ProductImg();
                    $prdImg->setPrdName($filename);
                    $prdImg->setProduct($newProduit);
                    $productImgRepo->save($prdImg, true);
                }
            }
            $produitRepo->save($newProduit, true);
            return $this->redirectToRoute('admin_adminindex');
        }
        return $this->render('admin/newproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /***
     * Suppression d'un article
     */
    #[Route('/del_product/{productid}', name: 'admindelproduct')]
    public function DelProduct($productid, ProductImgRepository $productImgRepo, CartRepository $cartRepo, PanierProduitRepository $panierRepo, ProductRepository $produitRepo, #[Autowire('%product_photo_dir%')] string $photoDir, Request $request)
    {
        $product = $produitRepo->findOneBy(['id' => $productid]);
        $panierPro = $product->getPanierProduits();
        $imgproduct = $product->getProductImgs();
        // Supprimer produitPanier et set nouveau price cart
        foreach($panierPro as $panier){
            $cart = $panier->getCart();
            
            $panierRepo->remove($panier, true);
            $cart->setTotalPrice($cart->getTotalPrice() - $product->getPrice());
            $cartRepo->save($cart, true);
        }
        // Supprimer Les images du produit et supprimer le produit
        foreach($imgproduct as $img){
            $productImgRepo->remove($img, true);
        }
        $produitRepo->remove($product, true);
        return $this->redirectToRoute('admin_adminindex');
    }

    /***
     * Modification d'un article
     */
    #[Route('/modifproduct/{idProduct}', name: 'modif_product')]
    public function ModifProduct($idProduct, ProductImgRepository $productImgRepo, ProductRepository $produitRepo, #[Autowire('%product_photo_dir%')] string $photoDir, Request $request): Response
    {
        $product = $produitRepo->findOneBy(['id' => $idProduct]);
        $form = $this->createForm(ProductAddTypeFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form['product_image']->getData();
            if($img){
                $filename = $this->createFolderImg($photoDir, $img, $product);
                $product->setProductImage($filename);
            }
            if($multipleImg = $form['productImgs']->getData()){
                $filenames = $this->createFolderImgs($photoDir, $multipleImg, $product);
                foreach($filenames as $filename){
                    $prdImg = new ProductImg();
                    $prdImg->setPrdName($filename);
                    $prdImg->setProduct($product);
                    $productImgRepo->save($prdImg, true);
                }
            }
           $produitRepo->save($product, true);
           return $this->redirectToRoute('admin_adminindex');
        }
        return $this->render('admin/newproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /***
     * Création d'un article
     */
    #[Route('/newcategory', name: 'newcategory')]
    public function newCategory(CategoryRepository $categoryRepo, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepo->save($category, true);
            return $this->redirectToRoute('admin_adminindex');
        }
        return $this->render('admin/addcategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /***
     * Création, upload d'images pour produit
     * Ajout d'un dossier si inexistant et upload
     * des images dans ce dernier
     */
    public function createFolderImg($photoDir, $img, $newProduit){
        $cheminDossierProduit = $photoDir . '/' . $newProduit->getId();
        $cheminDossierMini = $cheminDossierProduit . '/mini';

        $filename = bin2hex(random_bytes(6)) . '.' . $img->guessExtension();
        if (!is_dir($cheminDossierMini)) {
            mkdir($cheminDossierMini, 0777, true);
            $img->move($cheminDossierMini, $filename);
        }else{
            $objects = scandir($cheminDossierMini);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    $cheminFichier = $cheminDossierMini . '/' . $object;
                    if (is_file($cheminFichier)) {
                        unlink($cheminFichier); // Supprime les fichiers dans le dossier 'mini'
                    }
                }
            }
            rmdir(strval($cheminDossierMini));
            $photoDir = $cheminDossierMini;
            mkdir($photoDir, 0777, true);
            $img->move($photoDir, $filename);
        }
        return $filename;
    }

    public function createFolderImgs($photoDir, $img, $newProduit){
        $cheminDossierProduit = $photoDir . '/' . $newProduit->getId();
        $filenames = [];

        foreach($img as $imgs){
            $filename = bin2hex(random_bytes(6)) . '.' . $imgs->guessExtension();
            if(!file_exists($cheminDossierProduit)){
                $photoDir = $cheminDossierProduit;
                mkdir($photoDir, 0777, true);
                $imgs->move($cheminDossierProduit, $filename);
            }else{
                $imgs->move($cheminDossierProduit, $filename);
            }
            $filenames[] = $filename;
        }
        return $filenames;
    }
}
