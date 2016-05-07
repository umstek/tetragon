<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Manager;
use AppBundle\Form\EmployeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{

    /**
     * @Route("/employees", name="employees", methods={"GET", "HEAD"})
     * //@Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // Collect employee objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee');
        if ($request->query->count() > 0) {
            $expected = ['id', 'name', 'address', 'phone', 'email', 'nic'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 6 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $employees = $repository->findBy($request->query->all());
            } else {
                $employees = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $employees = $repository->findAll();
        }

        if (count($employees) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No employees found for the query. ");
            return $this->redirectToRoute('search employees');
        }

        return $this->render(':Employee:index.html.twig', [
            'employees' => $employees,
            'employeetype' => '' // TODO: add type
        ]);
    }

    /**
     * @Route("/employees", name="add employee", methods={"POST"})
     * @Route("/employees.add", name="new employee", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Employee object to hold the collected data
        $employee = null;
        if ($request->request->has('employee')) {

            switch ($request->request->get('employee')['role']) {
                case 'manager':
                    $employee = new Manager();
                    break;
                case 'sales_clerk':
                    $employee = new Manager();
                    break;
                case 'technician':
                    $employee = new Manager();
                    break;
                default:
                    assert(false);
            }
        }
        
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created employee.");
            return $this->redirectToRoute('employees');
        }

        // Executed only if validation fails
        // Renders the add employee page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create employee page with a get request
        return $this->render(':Employee:create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
