<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Technician
 *
 * Represents a technician in the shop.
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeeRepository")
 */
class Technician extends Employee
{
    public function __construct()
    {
        $this->setRole("technician");
        $this->repairs = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RepairingOrder", mappedBy="technician")
     */
    private $repairs;

    /**
     * Add repair
     *
     * @param \AppBundle\Entity\RepairingOrder $repair
     *
     * @return Technician
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
