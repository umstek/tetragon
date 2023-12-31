<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as MisdAssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person
 *
 * Persistent entity state provider for Person-type classes.
 *
 * @ORM\MappedSuperclass()
 */
abstract class Person
{

    /**
     * @var string
     *
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     * @MisdAssertPhoneNumber()
     * @ORM\Column(name="phone", type="string", length=20)
     */
    protected $phone;

    /**
     * @var string
     *
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/^(20\d\d|\d\d)\d{7,7}(V|X)$/i", htmlPattern="(20\d\d|\d\d)\d{7,7}(V|X)",
     *     message="Please enter a valid National Identity Card Number.")
     * @ORM\Column(name="nic", type="string", length=11, nullable=true)
     */
    protected $nic;

}
