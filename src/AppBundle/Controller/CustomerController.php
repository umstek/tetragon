<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @Route("/customers", name="customers", methods={"GET", "HEAD"})
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // Collect customer objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer');

        if ($request->query->count() > 0) {
            $expected = ['id', 'name', 'address', 'phone', 'email', 'nic'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 6 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $customers = $repository->findBy($request->query->all());
            } else {
                $customers = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $customers = $repository->findAll();
        }

        if (count($customers) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No customers found for the query. ");
            return $this->redirectToRoute('search customers');
        }
        // else
        if ($request->query->count() > 0) {
            $this->addFlash('success', "Customers matched for the given criteria.");
        }
        return $this->render(':Customer:index.html.twig', [
            'customers' => $customers,
        ]);
    }

    /**
     * @Route("/customers.search", name="search customers")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        $nonempty = [];
        if ($request->request->has('customer')) { // customer form data
            foreach ($request->request->get('customer') as $key => $value) {
                if ($value != null and $value != '' and $key != '_token') { // omit the empty ones and the CSRF token
                    $nonempty[$key] = $value;
                }
            }
        }

        if (count($nonempty) == 0) { // no parameters submitted, meaning asking for the empty form
            return $this->render(':Customer:search.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('customers', $nonempty);
    }

    /**
     * @Route("/customers", name="add customer", methods={"POST"})
     * @Route("/customers.add", name="new customer", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created customer.");
            return $this->redirectToRoute('customers');
        }

        // Executed only if validation fails
        // Renders the add customer page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create customer page with a get request
        return $this->render(':Customer:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax/customers", name="ajax add customer", methods={"POST"})
     * @Route("/ajax/customers.add", name="ajax new customer", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAjaxAction(Request $request)
    {
        // Customer object to hold the collected data
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created customer.");
            return $this->render(':Customer:createAjax.xml.twig', [
                'data' => $customer->getId(),
                'form' => $form->createView()
            ]);
        }

        // Executed only if validation fails
        // Renders the add customer page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create customer page with a get request
        return $this->render(':Customer:createAjax.xml.twig', [
            'data' => 0,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/customers/{id}", name="view customer", methods={"GET"}, requirements={"id" : "\d+"})
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        // Collect customer object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer');
        $customer = $repository->find($id);

        // If not found, render a page with an all records and error message
        if ($customer == null) {
            $this->addFlash('error', "Customer with id $id not found. ");
            return $this->redirectToRoute('customers');
        }

        // If found, render the content
        return $this->render(':Customer:view.html.twig', [
            'customer' => $customer
        ]);
    }

    /**
     * FIXME first route should be PUT, but symfony has a bug
     * @Route("/customers/{id}", name="update customer", methods={"POST"}, requirements={"id" : "\d+"})
     * @Route("/customers/{id}.edit", name="edit customer", methods={"GET", "HEAD"}, requirements={"id" : "\d+"})
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifyAction(Request $request, $id)
    {
        // Collect customer object from the database
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('AppBundle:Customer')->find($id);

        if ($customer == null) { // Not found? ask to create.
            $this->addFlash('error', "Customer with id $id not found. Create new?");
            return $this->redirectToRoute('new customer');
        }

        // Found customer with id?
        $form = $this->createForm(CustomerType::class, $customer);
        if ($request->isMethod('POST')) { // and sent the new data with PUT?
            // FIXME this should be PUT, symfony has a bug which doesn't allow the PUT request to be handled
            $form->handleRequest($request); // this changes the original customer object accordingly
            if ($form->isValid()) { // Validation
                $em->flush(); // Permanently change the record in database

                $this->addFlash('success', "Updated customer.");
                return $this->redirectToRoute('customers');
            }

            return $this->render(':Customer:modify.html.twig', [
                'id' => $id,
                'form' => $form->createView()
            ]);
        }

        return $this->render(':Customer:modify.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ], new Response());

    }
}
