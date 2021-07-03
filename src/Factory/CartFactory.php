<?php
namespace App\Factory;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class CartFactory
 * @package App\Factory
 */
class CartFactory
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ){}

    private function getUser()
    {
        return $this->tokenStorage->getToken()?->getUser();
    }

    /**
     * Creates an order.
     *
     * @return Cart
     */
    public function create(): Cart
    {
        $cart = new Cart();
        $cart->setCreatedAt(new \DateTime());

        if(!empty($this->getUser())){
            $cart->setUser($this->getUser());
        }else{
            $cart->setSessionId(uniqid('cart'));
        }

        return $cart;
    }
}