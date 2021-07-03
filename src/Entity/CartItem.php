<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 * @ORM\Table(name="cart_item")
 */
class CartItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     */
    private $quantity;

    /**
     * @var $cartRef Cart|null
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cartRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCartRef(): ?Cart
    {
        return $this->cartRef;
    }

    public function setCartRef(?Cart $cartRef): self
    {
        $this->cartRef = $cartRef;

        return $this;
    }

    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param CartItem $item
     *
     * @return bool
     */
    public function equals(CartItem $item): bool
    {
        return $this->getProduct()->getId() === $item->getProduct()->getId();
    }

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    public function getTotal(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }
}
