<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ProductAddTypeFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
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
    public function index(ProductRepository $produitRepo, CategoryRepository $categoryRepo): Response
    {
        $category = $categoryRepo->findAll();
        $produits = $produitRepo->findBy([], null, 5);
        return $this->render('admin/index.html.twig', [
            'produits' => $produits,
            'category' => $category
        ]);
    }

    #[Route('/newproduct', name: 'adminnewproduct')]
    public function newProduct(EntityManagerInterface $entityManager, ProductRepository $produitRepo, #[Autowire('%product_photo_dir%')] string $photoDir, Request $request): Response
    {
        $newProduit = New Product();
        $form = $this->createForm(ProductAddTypeFormType::class, $newProduit);
        $form->handleRequest($request);
        
        if($newProduit->getProductImage() != null){
            $directory = $photoDir.'/'.$newProduit->getId();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepo->save($newProduit, true);
            if($img = $form['product_image']->getData()){
                $filename = bin2hex(random_bytes(6)) . '.' . $img->guessExtension();
                // Vérification de l'éxistance d'un fichier nommé à l'id de l'user
                if(!file_exists($photoDir.'/'.$newProduit->getId())){
                    $photoDir = $photoDir.'/'.$newProduit->getId();
                    mkdir($photoDir, 0777);
                }else{
                    $objects = scandir($photoDir.'/'.$newProduit->getId());
                    foreach ($objects as $object) {
                        if ($object != "." && $object != "..") {
                            if (filetype($directory."/".$object) == "dir"){
                                rmdir($directory."/".$object); 
                            }else{
                                unlink($directory."/".$object);
                            }
                        }
                    }
                    rmdir(strval($photoDir.'/'.$newProduit->getId()));
                    $photoDir = $photoDir.'/'.$newProduit->getId();
                    mkdir($photoDir, 0777);
                }
                $img->move($photoDir, $filename);
                $newProduit->setProductImage($filename);
            }
            $produitRepo->save($newProduit, true);
            return $this->redirectToRoute('admin_adminindex');
        }
        return $this->render('admin/newproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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
}
