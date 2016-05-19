<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RepairingItem;
use AppBundle\Form\RepairingItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RepairsInventoryController extends Controller
{
    /**
     * @Route("/repair_items")
     */
    public function indexAction()
    {
        return $this->render(':RepairsInventory:index.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/repair_items.search")
     */
    public function searchAction()
    {
        return $this->render(':RepairsInventory:search.html.twig', array(// ...
        ));
    }

    /**
     * @Route("/ajax/repair_items", name="ajax add repair item", methods={"POST"})
     * @Route("/ajax/repair_items.add", name="ajax new repair item", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAjaxAction(Request $request)
    {
        // Repairing item object to hold the collected data
        $item = new RepairingItem();
        $form = $this->createForm(RepairingItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created repairing item.");
            return $this->render('::ajaxFinished.xml.twig', [
                'id' => $item->getId()
            ]);
        }

        // Executed only if validation fails
        // Renders the add item page which does not list customers
        // No need to add a flash. Validation errors are displayed in-place
        // Also needed to render the create item page with a get request
        return $this->render(':RepairsInventory:createAjax.xml.twig', [
            'data' => 0,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/repair_items/{id}", name="view item", methods={"GET"}, requirements={"id" : "\d+"})
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function viewAction($id, Request $request)
    {

        // Collect selling item object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:RepairingItem');
        $item = $repository->find($id);

        // If not found, render a page with an all records and error message
        if ($item == null) {
            $this->addFlash('error', "Repair item with id $id not found. ");
            return $this->redirectToRoute('items');
        }

        // If found, render the content
        return $this->render(':RepairsInventory:view.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/repair_items/{id}.edit", name="edit repairing item", methods={"GET", "HEAD"}, requirements={"id" : "\d+"})
     * @Route("/repair_items/{id}", name="update repairing item", methods={"POST"}, requirements={"id" : "\d+"})
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifyAction(Request $request, $id)
    {

        // Collect selling_item object from the database
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:RepairingItem')->find($id);

        if ($item == null) { // Not found? ask to create.
            $this->addFlash('error', "Repairing item with id $id not found.");
            return $this->redirectToRoute('repairing items');
        }

        // Found selling_Item with id?
        $form = $this->createForm(RepairingItemType::class, $item);
        if ($request->isMethod('POST')) { // and sent the new data with PUT?
            // FIXME this should be PUT, symfony has a bug which doesn't allow the PUT request to be handled
            $form->handleRequest($request); // this changes the original customer object accordingly
            if ($form->isValid()) { // Validation
                $em->flush(); // Permanently change the record in database

                $this->addFlash('success', "Updated Repairing Item.");
                return $this->redirectToRoute('items');
            }

            return $this->render(':RepairsInventory:modify.html.twig', [
                'id' => $id,
                'form' => $form->createView()
            ]);
        }

        return $this->render(':RepairsInventory:modify.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ], new Response());
    }


}
