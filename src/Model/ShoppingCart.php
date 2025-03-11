<?php

namespace App\Model;

use Symfony\Component\Serializer\Attribute\Groups;

class ShoppingCart
{
    #[Groups(['shopping_cart.read'])]
    public array $items = [];

    public function addItem(ShoppingCartItem $item): void
    {
        $this->items[] = $item;
    }



}