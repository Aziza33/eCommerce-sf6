<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Products;


#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController
{
   
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
    #[Route('/{id}', name: 'details')]
    public function details(Products $product): Response
    {
        return $this->render('products/details.html.twig',  [
            'product' => $product
        ]);
    }

}
