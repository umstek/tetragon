<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepairingItem
 *
 * @ORM\Table(name="repairing_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RepairingItemRepository")
 */
class RepairingItem
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     * @ORM\Column(name="due", type="datetime")
     */
    private $due;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_repaired", type="boolean")
     */
    private $isRepaired;

    /**
     * @var SalesOrder
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RepairingOrder", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return RepairingItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * Set name
     *
     * @param string $name
     *
     * @return RepairingItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get due
     *
     * @return \DateTime
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * Set due
     *
     * @param \DateTime $due
     *
     * @return RepairingItem
     */
    public function setDue($due)
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get isRepaired
     *
     * @return boolean
     */
    public function getIsRepaired()
    {
        return $this->isRepaired;
    }

    /**
     * Set isRepaired
     *
     * @param boolean $isRepaired
     *
     * @return RepairingItem
     */
    public function setIsRepaired($isRepaired)
    {
        $this->isRepaired = $isRepaired;

        return $this;
    }

    /**
     * Get order
     *
     * @return RepairingOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param RepairingOrder $order
     *
     * @return RepairingItem
     */
    public function setOrder(RepairingOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }
}
