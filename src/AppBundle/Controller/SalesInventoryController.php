<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesInventoryController extends Controller
{
    /**
     * @Route("/selling_items/")
     */
    public function indexAction()
    {
        return $this->render(':SalesInventory:index.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/selling_items.add")
     */
    public function createAction()
    {
        return $this->render(':SalesInventory:create.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/selling_items/{id}.view")
     */
    public function viewAction($id)
    {
        return $this->render(':SalesInventory:view.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/selling_items/{id}.edit")
     */
    public function modifyAction($id)
    {
        return $this->render(':SalesInventory:modify.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/selling_items.search")
     */
    public function searchAction()
    {
        return $this->render(':SalesInventory:search.html.twig', array(// ...
        ));
    }

}
