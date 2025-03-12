<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\ShoppingCart;
use App\Model\ShoppingCartItem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService
{
    public const SHOPPING_CART = "shoppingCart";

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getShoppingCart(): ShoppingCart
    {
        $session = $this->getSession();
        if (!$session->has(self::SHOPPING_CART)) {
            $session->set(self::SHOPPING_CART, new ShoppingCart());
        }
        return $session->get(self::SHOPPING_CART);

    }

    public function addItemToShoppingCart(Product $product): void
    {
        $shoppingCart = $this->getShoppingCart();
        $existingItem = $this->getExistingShoppingCartItem($product);

        if ($existingItem) {
            $existingItem->quantity++;
        } else {
            $shoppingCart->items->add(new ShoppingCartItem($product,1));
        }

        $this->getSession()->set(self::SHOPPING_CART, $shoppingCart);
    }

    public function removeItemFromCart(Product $product): void
    {
        $shoppingCart = $this->getShoppingCart();
        $existingItem = $this->getExistingShoppingCartItem($product);

        if ($existingItem === null) {
            return;
        }
        $shoppingCart->items->removeElement($existingItem);
        $newIndexValues = array_values($shoppingCart->items->toArray());
        $shoppingCart->items = new ArrayCollection($newIndexValues);

        $this->getSession()->set(self::SHOPPING_CART, $shoppingCart);
    }

    private function getExistingShoppingCartItem(Product $product): ?ShoppingCartItem
    {
        $existingItem = $this->getShoppingCart()
        ->items
            ->filter(fn(ShoppingCartItem $item) => $item->product->getId() === $product->getId())
            ->first();
        ;
        if($existingItem === false) {
            return null;
        }

        return $existingItem;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();

    }

}