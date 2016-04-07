<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * Represents a customer.
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=20, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nic", type="string", length=11, nullable=true)
     */
    private $nic;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuyingOrder", mappedBy="customer")
     */
    private $buyings;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RepairingOrder", mappedBy="customer")
     */
    private $repairs;

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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Constructor
     */
    public function __construct()
    {
        $this->buyings = new ArrayCollection();
        $this->repairs = new ArrayCollection();
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
     * @param \AppBundle\Entity\BuyingOrder $buying
     *
     * @return Customer
     */
    public function addBuying(\AppBundle\Entity\BuyingOrder $buying)
    {
        $this->buyings[] = $buying;

        return $this;
    }

    /**
     * Remove buying
     *
     * @param \AppBundle\Entity\BuyingOrder $buying
     */
    public function removeBuying(\AppBundle\Entity\BuyingOrder $buying)
    {
        $this->buyings->removeElement($buying);
    }

    /**
     * Get buyings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuyings()
    {
        return $this->buyings;
    }

    /**
     * Add repair
     *
     * @param \AppBundle\Entity\RepairingOrder $repair
     *
     * @return Customer
     */
    public function addRepair(\AppBundle\Entity\RepairingOrder $repair)
    {
        $this->repairs[] = $repair;

        return $this;
    }

    /**
     * Remove repair
     *
     * @param \AppBundle\Entity\RepairingOrder $repair
     */
    public function removeRepair(\AppBundle\Entity\RepairingOrder $repair)
    {
        $this->repairs->removeElement($repair);
    }

    /**
     * Get repairs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepairs()
    {
        return $this->repairs;
    }
}
