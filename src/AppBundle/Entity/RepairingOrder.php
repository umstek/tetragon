<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RepairingOrder
 *
 * @ORM\Table(name="repairing_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RepairingOrderRepository")
 */
class RepairingOrder extends Order
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RepairingItem", mappedBy="order")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer", inversedBy="repairs")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Technician", inversedBy="repairs")
     * @ORM\JoinColumn(name="technician_id", referencedColumnName="id")
     */
    private $technician;

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
     * @return RepairingOrder
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Add item
     *
     * @param RepairingItem $item
     *
     * @return RepairingOrder
     */
    public function addItem(RepairingItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param RepairingItem $item
     */
    public function removeItem(RepairingItem $item)
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
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     *
     * @return RepairingOrder
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get technician
     *
     * @return Technician
     */
    public function getTechnician()
    {
        return $this->technician;
    }

    /**
     * Set technician
     *
     * @param Technician $technician
     *
     * @return RepairingOrder
     */
    public function setTechnician(Technician $technician = null)
    {
        $this->technician = $technician;

        return $this;
    }
}
