<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
    * @Route("/product", name="app_product")
    */
    public function producttest(): Response
    {
        return $this->json([
            'name' => 'Product 1',
            'description' => 'description 1',
            'prix' => '120'
        ]);
    }

    /**
     * @Route("/api/product/post", name="app_product2")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $product = new Product();
        $param = array();
        $param = json_decode($request->getContent(), true);
         
        $product->setName($param['name']);
        $product->setPrix($param['prix']);
        $product->setDescription($param['description']);
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $message = "Product est bien ajouté";
        return new JsonResponse(array("message: $message"));
        
    }
    
    /**
     * @Route("/api/product/update/{id}", name="app_product3")
     */
    public function update(ManagerRegistry $doctrine, Request $request, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'produit non trouve pour l id  '.$id
            );
        }
        $param = array();
        $param = json_decode($request->getContent(), true);
         
        $product->setName($param['name']);
        $product->setPrix($param['prix']);
        $product->setDescription($param['description']);
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $message = "Product est bien modifié";
        return new JsonResponse(array("message: $message"));
        
    }

    /**
     * @Route("/api/product/delete/{id}", name="app_product4")
     */
    public function delete(ManagerRegistry $doctrine, Request $request, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'produit non trouve pour l id  '.$id
            );
        }
        
        $entityManager->remove($product);
        $entityManager->flush();
        $message = "Produit bien supprimé";
        return new JsonResponse(array("message: $message"));
        
        
    }
    /**
     * @Route("/api/product/affiche/", name="app_product5")
     */
    public function affiche(ManagerRegistry $doctrine, Request $request): Response
    {
        $product = $doctrine->getRepository(Product::class)->findAll();

        if (!$product) {
            throw $this->createNotFoundException(
                'aucun produit trouvé'
            );
        }
        return $this->json($product);    
    }
}