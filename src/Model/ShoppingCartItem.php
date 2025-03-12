<?php

namespace App\Model;

use App\Entity\Product;
use Symfony\Component\Serializer\Attribute\Groups;

class ShoppingCartItem
{
public function __construct(
    #[Groups(["shopping_cart.read"])]
    public Product $product,
    #[Groups(["shopping_cart.read"])]
    public int $quantity,
){}
}