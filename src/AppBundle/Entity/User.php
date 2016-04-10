<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="system_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(mappedBy="sysUser", targetEntity="AppBundle\Entity\Employee")
     */
    protected $profile;

    /**
     * Get profile
     *
     * @return Employee
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set profile
     *
     * @param Employee $profile
     *
     * @return User
     */
    public function setProfile(Employee $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }
}
