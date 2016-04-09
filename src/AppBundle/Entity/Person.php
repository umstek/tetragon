<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Person
 *
 * Persistent entity state provider for Person-type classes.
 *
 * @MappedSuperclass()
 */
abstract class Person
{

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=20, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nic", type="string", length=11, nullable=true)
     */
    protected $nic;

}
