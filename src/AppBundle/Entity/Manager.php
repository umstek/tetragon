<?php
/**
 * Created by PhpStorm.
 * User: Wickramaranga
 * Date: 4/7/2016
 * Time: 7:55 PM
 */

namespace AppBundle\Entity;


class Manager extends Employee
{
    public function __construct()
    {
        $this->setRole("manager");
    }
}