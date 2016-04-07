<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Seller
 *
 * Represents a salesman in the shop.
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 */
class Seller extends Employee
{
    public function __construct()
    {
        $this->setRole("seller");
        $this->sales = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuyingOrder", mappedBy="seller")
     */
    private $sales;

    /**
     * Add sale
     *
     * @param \AppBundle\Entity\BuyingOrder $sale
     *
     * @return Seller
     */
    public function addSale(\AppBundle\Entity\BuyingOrder $sale)
    {
        $this->sales[] = $sale;

        return $this;
    }

    /**
     * Remove sale
     *
     * @param \AppBundle\Entity\BuyingOrder $sale
     */
    public function removeSale(\AppBundle\Entity\BuyingOrder $sale)
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
}
