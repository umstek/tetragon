<?php

namespace AppBundle\Entity;

class Business
{
    private $manager;

    private $date;

    private $items;

    private $customer;

    private $seller;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function addItem(\AppBundle\Entity\SellingItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    public function removeItem(\AppBundle\Entity\SellingItem $item)
    {
        $this->items->removeElement($item);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setSeller(\AppBundle\Entity\Seller $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    public function getSeller()
    {
        return $this->seller;
    }
}