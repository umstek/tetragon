<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomerController extends Controller
{
    /**
     * @Route("/customer/")
     * //@Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $customer = new Customer();
        $customer->setName("test");
        $customer->setAddress("test");
        $customer->setEmail("test");
        $customer->setNic("test");
        $customer->setPhone("test");
        $form = $this->createForm(CustomerType::class, $customer);
        return $this->render(':Customer:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/add")
     */
    public function addAction()
    {
        return $this->render(':Customer:add.html.twig', array(// ...
        ));
    }

}
