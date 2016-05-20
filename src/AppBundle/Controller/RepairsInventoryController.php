<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RepairingItem;
use AppBundle\Form\RepairingItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RepairsInventoryController extends Controller
{
    /**
     * @Route("/repair_items", name="repairing items", methods={"GET","HEAD"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // Collect item objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:RepairingItem');

        if ($request->query->count() > 0) {
            $expected = ['id', 'name', 'price', 'description', 'due'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 5 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $items = $repository->findBy($request->query->all());
            } else {
                $items = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $items = $repository->findBy(['isRepaired' => false]);
        }

        if (count($items) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No items found for the query. ");
            return $this->redirectToRoute('search repairing items');
        }
        // else
        if ($request->query->count() > 0) {
            $this->addFlash('success', "items matched for the given criteria.");
        }
        return $this->render(':RepairsInventory:index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/repair_items.search", name="search repairing items")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $item = new RepairingItem();
        $form = $this->createForm(RepairingItemType::class, $item);

        $nonempty = [];
        if ($request->request->has('repairing_item')) { // Selling item form data
            foreach ($request->request->get('repairing_item') as $key => $value) {
                if ($value != null and $value != '' and $key != '_token') { // omit the empty ones and the CSRF token
                    $nonempty[$key] = $value;
                }
            }
        }

        if (count($nonempty) == 0) { // no parameters submitted, meaning asking for the empty form
            if ($request->isMethod('POST')) {
                $this->addFlash('info', 'Please provide some known information. ');
            }
            return $this->render(':RepairsInventory:search.html.twig', [
                'form' => $form->remove("price")->remove('due')->remove('isRepaired')->createView()
            ]);
        }
        return $this->redirectToRoute('repairing items', $nonempty);
    }

    /**
     * @Route("/ajax/repair_items", name="ajax add repair item", methods={"POST"})
     * @Route("/ajax/repair_items.add", name="ajax new repair item", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
                'id' => $item->getId(),
            ]);
        } else {
            if ($request->isMethod('POST')) {
                $this->addFlash('error', 'Form contains errors. ');
            }
        }
        
        return $this->render(':RepairsInventory:createAjax.xml.twig', [
            'name' => '',
            'id' => 0,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/repair_items/{id}", name="view repairing item", methods={"GET"}, requirements={"id" : "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
        } elseif ($item->getIsRepaired()) {
            $this->addFlash('warning', "You cannot edit an already repaired item. ");
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
                return $this->redirectToRoute('repairing items');
            }

            return $this->render(':RepairsInventory:modify.html.twig', [
                'id' => $id,
                'form' => $form->createView()
            ]);
        } else {
            if ($request->isMethod('POST')) {
                $this->addFlash('error', 'Form contains errors. ');
            }
        }

        return $this->render(':RepairsInventory:modify.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ], new Response());
    }

}
