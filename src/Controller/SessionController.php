<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/session/shopping-cart')]
class SessionController extends AbstractController
{
    public function __construct(private readonly SessionService $sessionService)
    {

    }

    #[Route('/', name: 'session_get_shopping_cart', methods: ['GET'])]
    public function getShoppingCart() : Response
    {

        return $this->json($this->sessionService->getShoppingCart(),context: [
            "groups"=> ['shopping_cart.read'],
        ]);

    }

    #[Route('/{id}', name: 'session_add_item_to_shopping_cart', methods: ['POST'])]
    public function addItemToShoppingCart(Product $product) : Response
    {

        if($product){
            $this->sessionService->addItemToShoppingCart($product);
        }

        return $this->json($this->sessionService->getShoppingCart(),context: [
            "groups"=>['shopping_cart.read'],
        ]);


    }

    #[Route('/{id}', name: 'session_remove_item_to_shopping_cart', methods: ['DELETE'])]
    public function removeItemToShoppingCart(Product $product) : Response
    {

        if($product){
            $this->sessionService->removeItemFromCart($product);
        }

        return $this->json($this->sessionService->getShoppingCart(),context:
            [
                "groups"=>['shopping_cart.read']
            ]);

    }

}