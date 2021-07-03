<?php


namespace App\Tests\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CartTest extends KernelTestCase
{
    public static $count = 0;

    private function initTestCart()
    {
        return new Cart();
    }

    private function initTestProduct($unique = false)
    {
        $product = new Product();
        if ($unique) {
            $product->setId($this::$count);
            $this::$count++;
        }
        $product->setName('TEST-PRODUCT');
        $product->setPrice('100');
        $product->setDescription('TEST description');

        return $product;
    }

    private function initTestCartItem(Cart $cart, $unique = false)
    {
        $cartItem = new CartItem();
        $cartItem->setQuantity(1);
        $cartItem->setProduct($this->initTestProduct($unique));
        $cartItem->setCartRef($cart);

        return $cartItem;
    }

    private function addTestItems(Cart $cart, $num, $unique = false)
    {
        for ($i = 1; $i <= $num; $i++) {
            $cartItem = $this->initTestCartItem($cart, $unique);
            $cart->addItem($cartItem);
        }

        return $cart;
    }

    public function testIsEmpty()
    {
        $cart = $this->initTestCart();
        $this->assertEquals(true, $cart->isEmpty());
    }

    public function testIsNotEmpty()
    {
        $cart = $this->initTestCart();
        $cartItem = $this->initTestCartItem($cart);

        $cart->addItem($cartItem);

        $this->assertEquals(false, $cart->isEmpty());
    }

    public function testAddItem()
    {
        $cart = $this->initTestCart();
        $cart = $this->addTestItems($cart, 2);

        $testProduct = $this->initTestProduct();

        foreach ($cart->getItems() as $item) {
            $this->assertEquals($testProduct->getName(), $item->getProduct()->getName());
        }
    }

    public function testCountAll()
    {
        $cart = $this->initTestCart();
        $cart = $this->addTestItems($cart, 4, true);

        $this->assertEquals(4, $cart->getItems()->count());
    }

    public function testUpdateWhenAddedExistingItem()
    {
        $cart = $this->initTestCart();
        $cartItem = $this->initTestCartItem($cart);

        $cart->addItem($cartItem);
        $this->assertEquals(1, $cartItem->getQuantity());

        $cart->addItem($cartItem);
        $this->assertEquals(2, $cartItem->getQuantity());
    }
}