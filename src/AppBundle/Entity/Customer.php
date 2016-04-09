<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Customer
 *
 * Represents a customer.
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer extends Person
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SalesOrder", mappedBy="customer")
     */
    private $buyings;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RepairingOrder", mappedBy="customer")
     */
    private $repairs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buyings = new ArrayCollection();
        $this->repairs = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nic
     *
     * @param string $nic
     *
     * @return Customer
     */
    public function setNic($nic)
    {
        $this->nic = $nic;

        return $this;
    }

    /**
     * Get nic
     *
     * @return string
     */
    public function getNic()
    {
        return $this->nic;
    }

    /**
     * Add buying
     *
     * @param SalesOrder $buying
     *
     * @return Customer
     */
    public function addBuying(SalesOrder $buying)
    {
        $this->buyings[] = $buying;

        return $this;
    }

    /**
     * Remove buying
     *
     * @param SalesOrder $buying
     */
    public function removeBuying(SalesOrder $buying)
    {
        $this->buyings->removeElement($buying);
    }

    /**
     * Get buyings
     *
     * @return Collection
     */
    public function getBuyings()
    {
        return $this->buyings;
    }

    /**
     * Add repair
     *
     * @param RepairingOrder $repair
     *
     * @return Customer
     */
    public function addRepair(RepairingOrder $repair)
    {
        $this->repairs[] = $repair;

        return $this;
    }

    /**
     * Remove repair
     *
     * @param RepairingOrder $repair
     */
    public function removeRepair(RepairingOrder $repair)
    {
        $this->repairs->removeElement($repair);
    }

    /**
     * Get repairs
     *
     * @return Collection
     */
    public function getRepairs()
    {
        return $this->repairs;
    }
}
