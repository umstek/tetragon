<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Manager;
use AppBundle\Entity\SalesClerk;
use AppBundle\Entity\Technician;
use AppBundle\Form\EmployeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmployeeController extends Controller
{

    /**
     * @Route("/employee/{type}/")
     * //@Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction($type)
    {
        $employee = null;
        switch ($type) {
            case 'technician':
                $employee = new Technician();
                break;
            case 'salesclerk':
                $employee = new SalesClerk();
                break;
            case 'manager':
                $employee = new Manager();
                break;
            default:
                throw new \Exception();
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        return $this->render(':Employee:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
