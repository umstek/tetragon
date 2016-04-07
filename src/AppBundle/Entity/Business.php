<?php

namespace AppBundle\Entity;

class Business
{
    private $manager;

    private $seller = array ();

    private $customer = array ();

    private $repairingItem = array ();

    private $sellerItems = array ();
    
    private $technician = array ();

    public function __construct()
    {
    }

}