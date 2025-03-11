<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\ShoppingCart;
use App\Model\ShoppingCartItem;
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

    public function addItemToCart(Product $product): void
    {
        $shoppingCart = $this->getShoppingCart();
        $existingItem = $this->getExistingShoppingCartItem($product);

        if ($existingItem) {
            $existingItem->quantity++;
        } else {
            $shoppingCartItem = new ShoppingCartItem($product, 1);
            $shoppingCart->addItem($shoppingCartItem);
        }

        $this->getSession()->set(self::SHOPPING_CART, $shoppingCart);
    }

    public function removeItemFromCart(Product $product): void
    {
        $shoppingCart = $this->getShoppingCart();
        $existingItem = $this->getExistingShoppingCartItem($product);
        if(null === $existingItem) {
            return;
        }
        if($existingItem->quantity > 1) {
            $existingItem->quantity--;
        } else {
            foreach ($shoppingCart->items as $index => $item) {
                if($item->product['id'] === $product->getId()) {
                    unset($shoppingCart->items[$index]);
                    break;
                }
            }

            $shoppingCart->items  = array_values($shoppingCart->items);
        }
        $this->getSession()->set(self::SHOPPING_CART, $shoppingCart);

    }

    private function getExistingShoppingCartItem(Product $product) : ?ShoppingCartItem
    {
        foreach ($this->getShoppingCart()->items as $item) {
            if ($item->product['id'] === $product->getId()) {
                return $item;
            }
        }

        return null;

    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();

    }

}