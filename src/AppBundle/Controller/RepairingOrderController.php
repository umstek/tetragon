<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RepairingOrder;
use AppBundle\Form\RepairingOrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RepairingOrderController extends Controller
{

    /**
     * @Route("/repairing_orders", name="add repairing order", methods={"POST"})
     * @Route("/repairing_orders.add", name="new repairing order", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Repairing order object to hold the collected data
        $repairingOrder = new RepairingOrder();
        $form = $this->createForm(RepairingOrderType::class, $repairingOrder);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            dump($request);

            $customer = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer')
                ->find($request->request->get('repairing_order')['customerId']);
            $technician = $this->getDoctrine()->getManager()->getRepository('AppBundle:Technician')
                ->find($request->request->get('repairing_order')['technicianId']);
            $repairingOrder->setCustomer($customer);
            $repairingOrder->setTechnician($technician);
            $customer->addRepair($repairingOrder);
            $technician->addRepair($repairingOrder);

            // TODO Add items

            $em = $this->getDoctrine()->getManager();
            $em->persist($repairingOrder);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created repairingOrder.");
            return $this->redirectToRoute('add repairing order');
        }

        return $this->render(':RepairingOrder:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
