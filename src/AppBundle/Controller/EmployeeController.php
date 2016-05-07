<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmployeeController extends Controller
{

    /**
     * @Route("/employees", name="employees", methods={"GET", "HEAD"})
     * //@Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        // Collect customer objects from the database
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
}
