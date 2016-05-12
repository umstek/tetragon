<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ItemInquiry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesInventoryController extends Controller
{
    /**
     * @Route("/selling_itemss", name="items",methods={"GET","HEAD"})
     * @param Request $request
     * @return Response
     */


    public function indexAction(Request $request)
    {
        // Collect item objects from the database
        // Get parameters are used for searching or filtering
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:SellingItem');

        if ($request->query->count() > 0) {
            $expected = ['name', 'brand', 'model', 'category', 'serial', 'price', 'description'];
            if (count(array_intersect($expected, $request->query->keys())) > 0 // at least one expected key
                and count($expected + $request->query->keys()) == 7 // and no unknown keys (using array union)
            ) {
                // Only the queries with expected keys are checked
                $items = $repository->findBy($request->query->all());
            } else {
                $items = $repository->findAll();
                $this->addFlash('error', "The query is invalid. Everything is shown. ");
            }
        } else { // No get params given
            $items = $repository->findAll();
        }

        if (count($items) == 0 && $request->query->count() > 0) {  // none found for the query
            $this->addFlash('info', "No items found for the query. ");
            return $this->redirectToRoute('search items');
        }
        // else
        if ($request->query->count() > 0) {
            $this->addFlash('success', "items matched for the given criteria.");
        }
        return $this->render(':SalesInventory:index.html.twig', [
            'items' => $items,
        ]);


    }

    /**
     * @Route("/selling_items", name="add item", methods={"POST"})
     * @Route("/selling_items.add", name="new item", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     *
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $item = new ItemInquiry();
        $form = $this->createForm(SellingItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created itemInquiry.");
            return $this->redirectToRoute('items');
        }


        return $this->render(':SalesInventory:create.html.twig', [
            'form' => $form->createView()
        ]);


    }

    /**
     * @Route("/selling_items/{id}.view")
     */
    public function viewAction($id)
    {


    }

    /**
     * @Route("/selling_items/{id}.edit")
     */
    public function modifyAction($id)
    {


    }

    /**
     * @Route("/selling_items.search")
     */
    public function searchAction()
    {


    }

}
