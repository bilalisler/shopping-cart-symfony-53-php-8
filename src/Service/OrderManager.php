<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Persists the cart in database and session.
     *
     * @param Order $order
     */
    public function save(Order $order): void
    {
        // Persist in database
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->entityManager->refresh($order);
    }
}