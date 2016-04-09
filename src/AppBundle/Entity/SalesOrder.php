<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BuyingOrder
 *
 * @ORM\Table(name="buying_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SalesOrderRepository")
 */
class SalesOrder extends Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SellingItem", mappedBy="order")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="buyings")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SalesClerk", inversedBy="sales")
     * @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     */
    private $salesClerk;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SalesOrder
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\SellingItem $item
     *
     * @return SalesOrder
     */
    public function addItem(\AppBundle\Entity\SellingItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\SellingItem $item
     */
    public function removeItem(\AppBundle\Entity\SellingItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return SalesOrder
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \AppBundle\Entity\SalesClerk
     */
    public function getSalesClerk()
    {
        return $this->salesClerk;
    }

    /**
     * Set seller
     *
     * @param \AppBundle\Entity\SalesClerk $salesClerk
     *
     * @return SalesOrder
     */
    public function setSalesClerk(\AppBundle\Entity\SalesClerk $salesClerk = null)
    {
        $this->salesClerk = $salesClerk;

        return $this;
    }
}
