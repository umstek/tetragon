<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManageController extends Controller
{
    /**
     * @Route("/manage")
     */
    public function indexAction()
    {
        return $this->render(':Manage:index.html.twig', array(// ...
        ));
    }

}
