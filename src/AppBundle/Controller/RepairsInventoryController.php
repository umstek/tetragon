<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RepairsInventoryController extends Controller
{
    /**
     * @Route("/repair_items")
     */
    public function indexAction()
    {
        return $this->render(':RepairsInventory:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/repair_items.search")
     */
    public function searchAction()
    {
        return $this->render(':RepairsInventory:search.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/repair_items.add")
     */
    public function createAction()
    {
        
        return $this->render(':RepairsInventory:create.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/repair_items/{id}.view")
     */
    public function viewAction($id)
    {
        return $this->render(':RepairsInventory:view.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/repair_items/{id}.edit")
     */
    public function editAction($id)
    {
        return $this->render(':RepairsInventory:edit.html.twig', array(
            // ...
        ));
    }

}
