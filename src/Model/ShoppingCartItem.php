<?php

namespace App\Model;

use App\Entity\Product;
use Symfony\Component\Serializer\Attribute\Groups;

class ShoppingCartItem
{
    #[Groups(['shopping_cart.read'])]
    public int $quantity;

    #[Groups(['shopping_cart.read'])]
    public array $product;

    public function __construct($product, int $quantity = 1)
    {
        $this->product = [
            'id' => $product->getId(),
            'name' => $product->getName(),
        ];
        $this->quantity = $quantity;
    }
}