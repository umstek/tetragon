<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SalesClerk
 *
 * Represents a salesman in the shop.
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 */
class SalesClerk extends Employee
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SalesOrder", mappedBy="salesClerk")
     */
    private $sales;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sales = new ArrayCollection();
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
     * @return SalesClerk
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
     * @return SalesClerk
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
     * @return SalesClerk
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
     * @return SalesClerk
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
     * @return SalesClerk
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
     * Add sale
     *
     * @param SalesOrder $sale
     *
     * @return SalesClerk
     */
    public function addSale(SalesOrder $sale)
    {
        $this->sales[] = $sale;

        return $this;
    }

    /**
     * Remove sale
     *
     * @param SalesOrder $sale
     */
    public function removeSale(SalesOrder $sale)
    {
        $this->sales->removeElement($sale);
    }

    /**
     * Get sales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set sysUser
     *
     * @param User $sysUser
     *
     * @return SalesClerk
     */
    public function setSysUser(User $sysUser = null)
    {
        $this->sysUser = $sysUser;

        return $this;
    }

    /**
     * Get sysUser
     *
     * @return User
     */
    public function getSysUser()
    {
        return $this->sysUser;
    }
}
