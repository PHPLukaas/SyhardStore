<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    use CreatedAtTrait ;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20 ,unique: true)]
    private ?string $reference = null;



    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Coupons $coupons = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $users = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: self::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, orphanRemoval: true)]
    private Collection $ordersDetails;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ordersDetails = new ArrayCollection();
        $this->created_at =new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }



    public function getCoupons(): ?Coupons
    {
        return $this->coupons;
    }

    public function setCoupons(?Coupons $coupons): self
    {
        $this->coupons = $coupons;

        return $this;
    }

    public function getUsers(): ?self
    {
        return $this->users;
    }

    public function setUsers(?self $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(self $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUsers($this);
        }

        return $this;
    }

    public function removeOrder(self $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUsers() === $this) {
                $order->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): self
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails->add($ordersDetail);
            $ordersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): self
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getOrders() === $this) {
                $ordersDetail->setOrders(null);
            }
        }

        return $this;
    }
}
