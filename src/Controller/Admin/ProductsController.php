<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController

{
#[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig');
    }

#[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
         // On refuse l'accès sauf si l'utilisateur est admin
         $this->denyAccessUnlessGranted('ROLE_ADMIN');

         //On crée un nouveau produit
         $product = new Products();

         // On crée le formulaire 
         $productForm = $this->createForm(ProductsFormType::class, $product);

         //on traite la requête du formulaire
         $productForm->handleRequest($request);
         
         // on vérifie si le formulaire est soumis ET valide
         if($productForm->isSubmitted() && $productForm->isValid()){
            //on arrondit le prix 
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);

            // on stocke 
            $em->persist($product);
            $em->flush();

            //$this->addFlash('success','Produit ajouté avec succès');

            // on redirige
            return $this->redirectToRoute('admin_products_index');
         }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);

        //autre façon possible selon Benoit
        // return $this->renderForm('admin/products/add.html.twig', compact('productForm')); ou ['productForm' => $productForm]


    }

#[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em): Response
    {
        //on vérifie si l'utilisateur peut éditer
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

         // on divise le prix par 100
        $prix = $product->getPrice() / 100;
        $product->setPrice($prix);

         // On crée le formulaire 
         $productForm = $this->createForm(ProductsFormType::class, $product);

         //on traite la requête du formulaire
         $productForm->handleRequest($request);
         
         // on vérifie si le formulaire est soumis ET valide
         if($productForm->isSubmitted() && $productForm->isValid()){
            
            
            //on arrondit le prix 
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);

            // on stocke 
            $em->persist($product);
            $em->flush();

            //$this->addFlash('success','Produit ajouté avec succès');

            // on redirige
            return $this->redirectToRoute('admin_products_index');
         }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView()
        ]);

        //autre façon possible selon Benoit
        // return $this->renderForm('admin/products/add.html.twig', compact('productForm')); ou ['productForm' => $productForm]

        //return $this->render('admin/products/index.html.twig');
    }

#[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        //on vérifie si l'utilisateur peut supprimer
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}

