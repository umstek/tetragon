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
            $expected = ['id', 'date', 'customerId', 'salesClerkId'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 4 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $salesOrders = $repository->findBySearchQuery($request->query->all());
            } else {
                $salesOrders = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $salesOrders = $repository->findAll();
        }

        if (count($salesOrders) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No salesOrders found for the query. ");
            return $this->redirectToRoute('sales orders');
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
        // Sales order object to hold the collected data
        $salesOrder = new SalesOrder();
        $form = $this->createForm(SalesOrderType::class, $salesOrder);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            dump($request);

            $customer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer')
                ->find($request->request->get('sales_order')['customerId']);
            $salesClerk = $this->getDoctrine()->getManager()->getRepository('AppBundle:SalesClerk')
                ->find($request->request->get('sales_order')['salesClerkId']);
            $salesOrder->setCustomer($customer);
            $salesOrder->setSalesClerk($salesClerk);
            $customer->addBuying($salesOrder);
            $salesClerk->addSale($salesOrder);

            // TODO Add items

            $em = $this->getDoctrine()->getManager();
            $em->persist($salesOrder);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created salesOrder.");
            return $this->redirectToRoute('add sales order');
        }
        
        return $this->render(':SalesOrder:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sales_orders/{id}", name="view sales order", methods={"GET"}, requirements={"id" : "\d+"})
     */
    public function viewAction(Request $request, $id)
    {
        // Collect salesOrder object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:SalesOrder');
        $salesOrder = $repository->find($id);

        // If not found, render a page with an all records and error message
        if ($salesOrder == null) {
            $this->addFlash('error', "Sales order with id $id not found. ");
            return $this->redirectToRoute('sales orders');
        }

        // If found, render the content
        return $this->render(':SalesOrder:view.html.twig', [
            'salesOrder' => $salesOrder
        ]);
    }
}