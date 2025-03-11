<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/products")]
class ProductControllerApi extends AbstractController
{
    public function __construct(private  readonly ProductRepository $productRepository)
    {
    }

    #[Route("/",methods: ["GET"])]
    public function getProducts() : JsonResponse
    {
      $products =   $this->productRepository->findAll();

        return $this->json($products,context: [
            "groups" => ["product.read"]
        ]);

    }

    #[Route("/{id}",methods: ["GET"])]
    public function getProductById(int $id) : JsonResponse
    {
        $product = $this->productRepository->find($id);
        if(!$product){
            return $this->json(["Error" => "Product not found."],status: Response::HTTP_NOT_FOUND);
        }
        return $this->json($product,status: Response::HTTP_OK,context:["groups" => ["product.read"]]);
    }



}