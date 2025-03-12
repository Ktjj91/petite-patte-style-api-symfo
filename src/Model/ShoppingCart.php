<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

class ShoppingCart
{
public function __construct(
    #[Groups(["shopping_cart.read"])]
    public ArrayCollection $items = new ArrayCollection())
{

}


}