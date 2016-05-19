<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SellingItem
 *
 * @ORM\Table(name="selling_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SellingItemRepository")
 */
class SellingItem
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
     * @Assert\NotBlank()
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @ORM\Column(name="serial", type="string", length=255) // TODO make unique
     */
    private $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_sold", type="boolean")
     */
    private $isSold = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_warranty_claimed", type="boolean")
     */
    private $isWarrantyClaimed = false;

    /**
     * @var \DateTime
     * 
     * @Assert\DateTime()
     * @ORM\Column(name="warranty_expiration", type="datetime", nullable=true)
     */
    private $warrantyExpiration;
    /**
     * @var
     *
     * @Assert\Range(min="1")
     * @ORM\Column(name="price", type="float")
     */
    private $price;
    /**
     * @var SalesOrder
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SalesOrder", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @return boolean
     */
    public function isIsWarrantyClaimed()
    {
        return $this->isWarrantyClaimed;
    }

    /**
     * Get isWarrantyClaimed
     *
     * @return boolean
     */
    public function getIsWarrantyClaimed()
    {
        return $this->isWarrantyClaimed;
    }

    /**
     * @param boolean $isWarrantyClaimed
     * @return SellingItem
     */
    public function setIsWarrantyClaimed($isWarrantyClaimed)
    {
        $this->isWarrantyClaimed = $isWarrantyClaimed;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getWarrantyExpiration()
    {
        return $this->warrantyExpiration;
    }

    /**
     * @param \DateTime $warrantyExpiration
     * @return SellingItem
     */
    public function setWarrantyExpiration($warrantyExpiration)
    {
        $this->warrantyExpiration = $warrantyExpiration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return SellingItem
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
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
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return SellingItem
     */
    public function setCategory($category)
    {
        $this->category = $category;

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
     * @return SellingItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @return SellingItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return SellingItem
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return SellingItem
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get serial
     *
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set serial
     *
     * @param string $serial
     *
     * @return SellingItem
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get isSold
     *
     * @return boolean
     */
    public function getIsSold()
    {
        return $this->isSold;
    }

    /**
     * Set isSold
     *
     * @param boolean $isSold
     *
     * @return SellingItem
     */
    public function setIsSold($isSold)
    {
        $this->isSold = $isSold;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\SalesOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\SalesOrder $order
     *
     * @return SellingItem
     */
    public function setOrder(\AppBundle\Entity\SalesOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }
}
