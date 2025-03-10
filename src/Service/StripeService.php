<?php

namespace App\Service;

use App\Entity\Product;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidArgumentException;
use Stripe\Price;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $stripeClient;

    /**
     * @throws InvalidArgumentException
     */
    public function getStripe() : StripeClient
    {
        return $this->stripeClient ??= new StripeClient($_ENV['STRIPE_API_SECRET']);
    }


    /**
     * @throws ApiErrorException
     */
    public function createProduct(Product $product) : \Stripe\Product
    {
        return $this->getStripe()->products->create([
            "name" => $product->getName(),
            "active" => $product->isActive(),
            "description" => $product->getDescription()
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    public function createPrice(Product $product): Price
    {
        return  $this->getStripe()->prices->create([
            "unit_amount" => $product->getPrice(),
            "currency" => "EUR",
            "product" => $product->getStripeProductId()

        ]);

    }

    /**
     * @throws ApiErrorException
     */
    public function updateProduct(Product $product): \Stripe\Product
    {
        return $this->getStripe()->products->update($product->getStripeProductId(), [
            "name" => $product->getName(),
            "description" => $product->getDescription(),
            "active" => $product->isActive()
        ]);

    }

    /**
     * @throws ApiErrorException
     */
    public function deactivatePrice(string $priceId): void
    {
        $this->getStripe()->prices->update($priceId, [
            'active' => false
        ]);
    }


}
