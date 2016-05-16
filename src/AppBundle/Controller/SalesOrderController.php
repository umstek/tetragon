<?php

namespace AppBundle\Controller;


use AppBundle\Entity\SalesOrder;
use AppBundle\Form\SalesOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderController extends Controller
{

    /**
     * @Route("/sales_orders", name="sales orders", methods={"GET", "HEAD"})
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // Collect sales objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:SalesOrder');

        if ($request->query->count() > 0) {
            $expected = ['id', 'date', 'customer_name'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 3 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $salesOrders = $repository->findBy($request->query->all());
            } else {
                $salesOrders = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $salesOrders = $repository->findAll();
        }

        if (count($salesOrders) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No salesOrders found for the query. ");
            return $this->redirectToRoute('search salesOrders');
        }
        // else
        if ($request->query->count() > 0) {
            $this->addFlash('success', "Sales orders matched for the given criteria.");
        }
        return $this->render(':SalesOrder:index.html.twig', [
            'sales' => $salesOrders,
        ]);
    }

    /**
     * @Route("/sales_orders", name="add sales order", methods={"POST"})
     * @Route("/sales_orders.add", name="new sales order", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $salesOrder = new SalesOrder();
        $form = $this->createForm(SalesOrderType::class, $salesOrder);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($salesOrder);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created salesOrder.");
            return $this->redirectToRoute('add sales order');
        }

        // Executed only if validation fails
        // Renders the add salesOrder page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create salesOrder page with a get request
        return $this->render(':SalesOrder:create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}