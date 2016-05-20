<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RepairingOrder;
use AppBundle\Form\RepairingOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RepairingOrderController extends Controller
{

    /**
     * @Route("/repairing_orders", name="repairing orders", methods={"GET", "HEAD"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // Collect sales objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:RepairingOrder');

        if ($request->query->count() > 0) {
            $expected = ['id', 'date', 'customerId', 'technicianId'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 4 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $repairOrders = $repository->findBy($request->query->all());
            } else {
                $repairOrders = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $repairOrders = $repository->findAll();
        }

        if (count($repairOrders) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No repair orders found for the query. ");
            return $this->redirectToRoute('repairing orders');
        }
        // else
        if ($request->query->count() > 0) {
            $this->addFlash('success', "Repair orders matched for the given criteria.");
        }
        return $this->render(':RepairingOrder:index.html.twig', [
            'repairs' => $repairOrders,
        ]);
    }

    /**
     * @Route("/repairing_orders", name="add repairing order", methods={"POST"})
     * @Route("/repairing_orders", name="ajax add repairing order", methods={"POST"})
     * @Route("/repairing_orders.add", name="new repairing order", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Sales order object to hold the collected data
        $repairingOrder = new RepairingOrder();
        $form = $this->createForm(RepairingOrderType::class, $repairingOrder);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            dump($request);

            if ($request->request->get('repairing_order')['customerId'] == "" or
                $request->request->get('repairing_order')['technicianId'] == ""
            ) {
                $this->addFlash('error', 'A customer and a technician must be specified.');
            } else {
                $customer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer')
                    ->find($request->request->get('repairing_order')['customerId']);
                $salesClerk = $this->getDoctrine()->getManager()->getRepository('AppBundle:Technician')
                    ->find($request->request->get('repairing_order')['technicianId']);
                $repairingOrder->setCustomer($customer);
                $repairingOrder->setTechnician($salesClerk);
                $customer->addRepair($repairingOrder);
                $salesClerk->addRepair($repairingOrder);

                $em = $this->getDoctrine()->getManager();
                $em->persist($repairingOrder);
                $em->flush(); // Permanently add to database

                $this->addFlash('success', "Created repair order. Start adding items!");

                // form submitted; have to be AJAX. Anyways, let's do a redirect if that doesn't happen
                if ($request->isXmlHttpRequest()) {  // if AJAX request and not valid
                    return $this->render('ajaxFinished.xml.twig', [
                        'id' => $repairingOrder->getId()
                    ]);
                } else {
                    return $this->redirectToRoute('add repairing order');
                }
            }
        }

        if ($request->isXmlHttpRequest()) {  // if AJAX request and not valid
            return $this->render(':RepairingOrder:createAjax.xml.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render(':RepairingOrder:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax/repairing_orders/{id}.add", name="ajax add item to repairing order", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addItemToOrderAjaxAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // request method is always POST
        // TODO other validation
        // assume everything works fine
        if ($request->request->has('itemId')) {
            $orderRepo = $em->getRepository('AppBundle:RepairingOrder');
            $order = $orderRepo->find($id);

            $itemRepo = $em->getRepository('AppBundle:RepairingItem');
            $item = $itemRepo->find($request->get('itemId'));
            if ($order != null && $item != null) {
                if (!$item->getIsRepaired()) {
                    $this->addFlash('success', 'Item added successfully. ');

                    $order->addItem($item);
                    $item->setOrder($order);

                    $em->flush();

                    return new Response(200);
                }
            }
        }

        $this->addFlash('error', 'Error occurred. ');
        return new Response(200);
    }

    /**
     * @Route("/repairing_orders/{id}", name="view repairing order", methods={"GET"}, requirements={"id" : "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        // Collect repairing order object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:RepairingOrder');
        $repairingOrder = $repository->find($id);

        // If not found, render a page with an all records and error message
        if ($repairingOrder == null) {
            $this->addFlash('error', "Repairing order with id $id not found. ");
            return $this->redirectToRoute('repairing orders');
        }

        // If found, render the content
        return $this->render(':RepairingOrder:view.html.twig', [
            'repairingOrder' => $repairingOrder
        ]);
    }
}
