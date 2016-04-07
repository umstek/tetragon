<?php
/**
 * Created by PhpStorm.
 * User: Wickramaranga
 * Date: 4/7/2016
 * Time: 7:57 PM
 */

namespace AppBundle\Entity;


class Technician extends Employee
{
    public function __construct()
    {
        $this->setRole("technician");
    }
}