<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Manager;
use AppBundle\Entity\SalesClerk;
use AppBundle\Entity\Technician;
use AppBundle\Form\EmployeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{

    /**
     * @Route("/employees", name="employees", methods={"GET", "HEAD"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        // Collect employee objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee');
        if ($request->query->count() > 0) {  // User is doing a search
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

        $employeeRoles = [];
        foreach ($employees as $employee) {
            if ($employee instanceof Manager) {
                $employeeRoles[$employee->getId()] = 'Manager';
            } elseif ($employee instanceof SalesClerk) {
                $employeeRoles[$employee->getId()] = 'Sales Clerk';
            } elseif ($employee instanceof Technician) {
                $employeeRoles[$employee->getId()] = 'Technician';
            } else {
                throw new \Exception('Invalid user type.');
            }
        }
        //else
        if ($request->query->count() > 0) {
            $this->addFlash('success', 'Found employees for the given criteria. ');
        }
        return $this->render(':Employee:index.html.twig', [
            'employees' => $employees,
            'employee_roles' => $employeeRoles
        ]);
    }

    /**
     * @Route("/employees.search", name="search employees")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $employee = null;
        $form = $this->createForm(EmployeeType::class, $employee);

        $nonempty = [];
        if ($request->request->has('employee')) { // customer form data
            foreach ($request->request->get('employee') as $key => $value) {
                if ($value != null and $value != '' and $key != '_token' and $key != 'role') {
                    // omit the empty ones and the CSRF token
                    $nonempty[$key] = $value;
                }
            }
        }

        if (count($nonempty) == 0) { // no parameters submitted, meaning asking for the empty form
            if ($request->isMethod('POST')) {
                $this->addFlash('info', 'Please provide some known information. ');
            }
            return $this->render(':Employee:search.html.twig', [
                'form' => $form->remove('sysUser')->remove('role')->createView()
            ]);
        }

        return $this->redirectToRoute('employees', $nonempty);
    }

    /**
     * @Route("/ajax/employees.search", name="ajax search employees by name", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function searchByNameAjaxAction(Request $request)
    {

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $role = $request->request->get('role');
            dump($request);
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee');
            $employees = $repository->findAllByLikeName($name, $role);

            if (count($employees) > 0) {
                return $this->render(':Employee:searchAjax.xml.twig', [
                    'employees' => $employees
                ]);
            } else {
                $this->addFlash('info', "No employees found for the query. ");
            }
        }

        return $this->render(':Employee:searchAjax.xml.twig', [
            'employees' => null
        ]);
    }

    /**
     * @Route("/employees", name="add employee", methods={"POST"})
     * @Route("/employees.add", name="new employee", methods={"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $employee = null;
        $roles = [];  // Access level control
        if ($request->request->has('employee')) {
            switch ($request->request->get('employee')['role']) {
                case 'manager':
                    $employee = new Manager();
                    $roles[] = 'ROLE_ADMIN';
                    break;
                case 'sales_clerk':
                    $employee = new SalesClerk();
                    $roles[] = 'ROLE_USER';
                    break;
                case 'technician':
                    $employee = new Technician();
                    $roles[] = 'ROLE_USER';
                    break;
                default:
                    throw new \Exception('Invalid user type.');
                    break;
            }
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);  // even though we handle, we are safe unless we persist data

        if ($form->isValid()) { // Validation
            if ($request->request->get('employee')['sysUser']['confirm_password']
                == $request->request->get('employee')['sysUser']['plain_password']
            ) {
                if ($request->request->get('employee')['sysUser']['email']
                    == $request->request->get('employee')['email']
                ) {
                    if ($request->request->get('employee')['sysUser']['plain_password'] == '') {
                        $form->addError(new FormError('Password cannot be empty.'));
                    } else {
                        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');

                        if (count($em->findBy(['email' => $request->request->get('employee')['email']])) > 0) {
                            $form->addError(new FormError('Email address already taken.'));
                        } elseif (count($em->findBy(['username' => $request->request->get('employee')['sysUser']['username']])) > 0) {
                            $form->addError(new FormError('Username already taken.'));
                        } else {

                            $employee->getSysUser()->setRoles($roles);  // Set access levels

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($employee);
                            $em->flush(); // Permanently add to database

                            $this->addFlash('success', "Created employee.");
                            return $this->redirectToRoute('employees');
                        }
                    }
                } else {
                    $form->addError(new FormError('Email addresses do not match.'));
                }
            } else {
                $form->addError(new FormError('Passwords do not match.'));
            }
        } else {
            if ($request->isMethod('POST')) {
                $this->addFlash('error', 'Form contains errors. ');
            }
        }

        // Executed only if validation fails
        // Renders the add employee page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create employee page with a get request
        return $this->render(':Employee:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/employees/{id}", name="view employee", methods={"GET"}, requirements={"id" : "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        // Collect employee object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Employee');
        $employee = $repository->find($id);
        echo $employee instanceof Manager;
        // If not found, render a page with an all records and error message
        if ($employee == null) {
            $this->addFlash('error', "Employee with id $id not found. ");
            return $this->redirectToRoute('employees');
        }

        // If found, render the content
        return $this->render(':Employee:view.html.twig', [
            'employee' => $employee
        ]);
    }

    /**
     * FIXME first route should be PUT, but symfony has a bug
     * @Route("/employees/{id}", name="update employee", methods={"POST"}, requirements={"id" : "\d+"})
     * @Route("/employees/{id}.edit", name="edit employee", methods={"GET", "HEAD"}, requirements={"id" : "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifyAction(Request $request, $id)
    {
        if ($this->getUser()->getProfile()->getId() != $id && !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            dump($this->getUser()->getId());
            throw $this->createAccessDeniedException('You are not allowed to perform this action.');
        }

        // Collect employee object from the database
        $em = $this->getDoctrine()->getManager();
        $employee = $em->getRepository('AppBundle:Employee')->find($id);

        if ($employee == null) { // Not found? ask to create.
            $this->addFlash('error', "Employee with id $id not found. Create new?");
            return $this->redirectToRoute('new employee');
        }

        // Found employee with id?
        $form = $this->createForm(EmployeeType::class, $employee);
        if ($request->isMethod('POST')) { // and sent the new data with PUT?
            // FIXME this should be PUT, symfony has a bug which doesn't allow the PUT request to be handled
            // TODO User promotion. Anything to manager. 
            if ($request->request->get('employee')['sysUser']['confirm_password']
                == $request->request->get('employee')['sysUser']['plain_password']
            ) {
                if ($request->request->get('employee')['sysUser']['email']
                    == $request->request->get('employee')['email']
                ) { // we are not safe, data is persisted automatically. So check first.
                    $form->handleRequest($request); // this changes the original employee object accordingly

                    if ($form->isValid()) { // Validation
                        $em->flush(); // Permanently change the record in database

                        $this->addFlash('success', "Updated employee.");
                        return $this->redirectToRoute('employees');
                    } else {
                        $this->addFlash('error', 'Form contains errors. ');
                    }
                } else {
                    $form->addError(new FormError('Email addresses do not match.'));
                }
            } else {
                $form->addError(new FormError('Passwords do not match.'));
            }

            return $this->render(':Employee:modify.html.twig', [
                'id' => $id,
                'form' => ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    ? $form->createView() : $form->remove('role')->createView()
            ]);
        }

        return $this->render(':Employee:modify.html.twig', [
            'id' => $id,
            'form' => ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                ? $form->createView() : $form->remove('role')->createView()
        ], new Response());

    }
}
