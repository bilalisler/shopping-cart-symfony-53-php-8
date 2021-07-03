<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="orderr")
 */
class Order
{
    // order statuses
    public const ORDER_STATUS_CANCELED = -1;
    public const ORDER_STATUS_COMPLETE = 0; // Sipariş Alındı
    public const ORDER_STATUS_APPROVED = 1; // Sipariş Onaylandı

    public const ORDER_STATUS_TEXTS = [
        self::ORDER_STATUS_CANCELED => 'Sipariş İptal Edildi',
        self::ORDER_STATUS_COMPLETE => 'Sipariş Alındı',
        self::ORDER_STATUS_APPROVED => 'Sipariş Onaylandı',
    ];

    // payment types
    public const PAYMENT_TYPE_PAY_AT_DOOR = 1; // kapıda ödeme
    public const PAYMENT_TYPE_CREDIT_CART = 2; // kredi kartı

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne (targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="orderRef", cascade={"persist","remove"})
     */
    private $items;

    /**
     * @var $status int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @var $address string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var $paymentType string|null
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $paymentType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $approvedAt;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        foreach ($this->getItems() as $existingItem) {
            // The item already exists, update the quantity
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $this;
            }
        }

        $this->items[] = $item;
        $item->setOrderRef($this);

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderRef() === $this) {
                $item->getOrderRef(null);
            }
        }

        return $this;
    }

    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeItems(): self
    {
        foreach ($this->getItems() as $item) {
            $this->removeItem($item);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Order
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     * @return Order
     */
    public function setStatus(?int $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return Order
     */
    public function setAddress(?string $address): Order
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    /**
     * @param string|null $paymentType
     * @return Order
     */
    public function setPaymentType(?string $paymentType): Order
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     * @return Order
     */
    public function setUpdatedAt(?\DateTime $updatedAt): Order
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getApprovedAt(): ?\DateTime
    {
        return $this->approvedAt;
    }

    /**
     * @param \DateTime|null $approvedAt
     * @return Order
     */
    public function setApprovedAt(?\DateTime $approvedAt): Order
    {
        $this->approvedAt = $approvedAt;
        return $this;
    }

}
