<?php

namespace AppBundle\Entity;

class Business
{
    private $manager;

    private $salesClerks = array();

    private $customers = array();

    private $repairingItems = array();

    private $sellingItems = array();

    private $technicians = array();

    public function __construct()
    {
    }

}