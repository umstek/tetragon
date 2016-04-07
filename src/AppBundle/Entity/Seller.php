<?php
/**
 * Created by PhpStorm.
 * User: Wickramaranga
 * Date: 4/7/2016
 * Time: 7:56 PM
 */

namespace AppBundle\Entity;


class Seller extends Employee
{
    public function __construct()
    {
        $this->setRole("seller");
    }
}