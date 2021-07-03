<?php

namespace App\Storage;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CartSessionStorage
{
    /**
     * @var string
     */
    const CART_KEY_NAME = 'cart_id';

    public function __construct(
        private SessionInterface $session,
        private CartRepository $cartRepository,
        private TokenStorageInterface $tokenStorage
    )
    {
    }

    private function getUser()
    {
        return $this->tokenStorage->getToken()?->getUser();
    }

    /**
     * Gets the cart in session.
     *
     * @return Cart|null
     */
    public function getCart(): ?Cart
    {
        if (!empty($this->getUser())) {
            return $this->cartRepository->findOneBy([
                'user' => $this->getUser()
            ]);
        } elseif (!empty($this->getCardSessionId())) {
            return $this->cartRepository->findOneBy([
                'sessionId' => $this->getCardSessionId()
            ]);
        }

        return null;
    }

    public function clearCart(): void
    {
        $this->session->remove(self::CART_KEY_NAME);
    }

    /**
     * Sets the cart session id.
     *
     * @param Cart $cart
     */
    public function setCart(Cart $cart): void
    {
        $this->session->set(self::CART_KEY_NAME, $cart->getSessionId());
    }

    /**
     * Returns the cart session id.
     *
     * @return string|null
     */
    private function getCardSessionId(): ?string
    {
        return $this->session->get(self::CART_KEY_NAME);
    }
}