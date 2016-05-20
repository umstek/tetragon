<?php

namespace AppBundle\Controller;


use AppBundle\Entity\SalesOrder;
use AppBundle\Form\SalesOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesOrderController extends Controller
{

    /**
     * @Route("/sales_orders", name="sales orders", methods={"GET", "HEAD"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
     * @Route("/sales_orders", name="ajax add sales order", methods={"POST"})
     * @Route("/sales_orders.add", name="new sales order", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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

            if ($request->request->get('sales_order')['customerId'] == "" or
                $request->request->get('sales_order')['salesClerkId'] == ""
            ) {
                $this->addFlash('error', 'A customer and a sales clerk must be specified.');
            } else {
                $customer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer')
                    ->find($request->request->get('sales_order')['customerId']);
                $salesClerk = $this->getDoctrine()->getManager()->getRepository('AppBundle:SalesClerk')
                    ->find($request->request->get('sales_order')['salesClerkId']);
                $salesOrder->setCustomer($customer);
                $salesOrder->setSalesClerk($salesClerk);
                $customer->addBuying($salesOrder);
                $salesClerk->addSale($salesOrder);

                $em = $this->getDoctrine()->getManager();
                $em->persist($salesOrder);
                $em->flush(); // Permanently add to database

                $this->addFlash('success', "Created sales order. Start adding items!");

                // form submitted; have to be AJAX. Anyways, let's do a redirect if that doesn't happen
                if ($request->isXmlHttpRequest()) {  // if AJAX request and not valid
                    return $this->render('ajaxFinished.xml.twig', [
                        'id' => $salesOrder->getId()
                    ]);
                } else {
                    return $this->redirectToRoute('add sales order');
                }
            }
        }

        if ($request->isXmlHttpRequest()) {  // if AJAX request and not valid
            return $this->render(':SalesOrder:createAjax.xml.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render(':SalesOrder:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sales_orders/{id}.add", name="ajax add item to sales order", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addItemToOrderAjaxAction(Request $request, $id)
    {
        dump($request);

        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            if ($request->request->has('itemId') && $request->request->has('expire')) {
                $orderRepo = $em->getRepository('AppBundle:SalesOrder');
                $order = $orderRepo->find($id);

                $itemRepo = $em->getRepository('AppBundle:SellingItem');
                $item = $itemRepo->find($request->get('itemId'));
                if ($order != null && $item != null) {
                    if (!$item->getIsSold()) {
                        $this->addFlash('success', 'Item added successfully. ');
                        $item->setIsSold(true);
                        try {
                            $item->setWarrantyExpiration(
                                new \DateTime($request->request->get('expire'),
                                    new \DateTimeZone('Asia/Colombo')));
                        } catch (\Exception $ex) {
                            dump($request->request->get('expire'));
                        } // FIXME warranty

                        $order->addItem($item);
                        $item->setOrder($order);

                        $em->flush();
                    } else {
                        $this->addFlash('warning', 'Item has already been sold.');
                        return $this->render(':SalesOrder:addItemToOrder.xml.twig');
                    }
                } else {
                    $this->addFlash('error', 'Invalid item (or nothing) provided.');
                    return $this->render(':SalesOrder:addItemToOrder.xml.twig');
                }
            } else {
                $this->addFlash('error', 'Required details not found.');
                return $this->render(':SalesOrder:addItemToOrder.xml.twig');
            }
        } // else

        return $this->render(':SalesOrder:addItemToOrder.xml.twig');
    }

    /**
     * @Route("/sales_orders/{id}", name="view sales order", methods={"GET"}, requirements={"id" : "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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