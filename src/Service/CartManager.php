<?php

namespace App\Service;

use App\Entity\Cart;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage,
        private CartFactory $orderFactory,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Gets the current cart.
     *
     * @return Cart
     */
    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            $cart = $this->orderFactory->create();
        }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Cart $cart
     */
    public function save(Cart $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Cart $cart
     */
    public function removeCart(Cart $cart): void
    {
        // Persist in database
        $this->entityManager->remove($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->clearCart();
    }
}