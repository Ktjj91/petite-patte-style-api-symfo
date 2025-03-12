<?php

namespace App\Controller;

use App\Service\SessionService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route("stripe/checkout/session")]
class StripeController extends AbstractController
{
    public function __construct(private readonly StripeService $stripeService)
    {

    }
    #[Route("",methods: ["POST"])]
    public function createCheckoutSession(SessionService $sessionService) :Response
    {

        return  $this->json([
            "url" => $this->stripeService->createCheckoutSession($sessionService->getShoppingCart())->url
        ]);

    }


}