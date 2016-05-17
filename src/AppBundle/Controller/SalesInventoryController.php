<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SellingItem;
use AppBundle\Form\SellingItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SalesInventoryController extends Controller
{

    /**
     * @Route("/selling_items", name="items",methods={"GET","HEAD"})
     *
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
            return $this->redirectToRoute('search selling_items');
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
        $item = new SellingItem();
        $form = $this->createForm(SellingItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created item.");
            return $this->redirectToRoute('new item');
        }

        return $this->render(':SalesInventory:create.html.twig', [
            'form' => $form->remove('isSold')->remove('isWarrantyClaimed')->createView()
        ]);
    }

    /**
     * @Route("/selling_items/{id}.view", name="view item", methods={"GET"}, requirements={"id" : "\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */

    public function viewAction($id)
    {

        // Collect selling item object from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:SellingItem');
        $item = $repository->find($id);

        // If not found, render a page with an all records and error message
        if ($item == null) {
            $this->addFlash('error', "selling item with id $id not found. ");
            return $this->redirectToRoute('items');
        }

        // If found, render the content
        return $this->render(':SalesInventory:view.html.twig', [
            'SalesInv' => $item
        ]);
    }


    /**
     * @Route("/selling_items/{id}.edit",name="edit item", methods={"GET", "HEAD"}, requirements={"id" : "\d+"})     *
     * @Route("/selling_items/{id}", name="update item", methods={"POST"}, requirements={"id" : "\d+"})     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifyAction(Request $request, $id)
    {

        // Collect selling_item object from the database
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:SellingItem')->find($id);

        if ($item == null) { // Not found? ask to create.
            $this->addFlash('error', "SellingItem with id $id not found. Create new?");
            return $this->redirectToRoute('new item');
        }

        // Found selling_Item with id?
        $form = $this->createForm(SellingItemType::class, $item);
        if ($request->isMethod('POST')) { // and sent the new data with PUT?
            // FIXME this should be PUT, symfony has a bug which doesn't allow the PUT request to be handled
            $form->handleRequest($request); // this changes the original customer object accordingly
            if ($form->isValid()) { // Validation
                $em->flush(); // Permanently change the record in database

                $this->addFlash('success', "Updated Selling Item.");
                return $this->redirectToRoute('items');
            }

            return $this->render(':SalesInventory:modify.html.twig', [
                'id' => $id,
                'form' => $form->createView()
            ]);
        }

        return $this->render(':SalesInventory:modify.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ], new Response());
    }

    /**
     * @Route("/selling_items.search", name="search selling_items")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $item = new SellingItem();
        $form = $this->createForm(SellingItemType::class, $item);

        $nonempty = [];
        if ($request->request->has('selling_item')) { // Selling item form data
            foreach ($request->request->get('selling_item') as $key => $value) {
                if ($value != null and $value != '' and $key != '_token') { // omit the empty ones and the CSRF token
                    $nonempty[$key] = $value;
                }
            }
        }
        dump($request);
        if (count($nonempty) == 0) { // no parameters submitted, meaning asking for the empty form
            return $this->render(':SalesInventory:search.html.twig', [
                'form' => $form->remove("warrantyExpiration")->remove("isWarrantyClaimed")->remove("isSold")->remove("price")->createView()
            ]);
        }

        return $this->redirectToRoute('items', $nonempty);

    }
}
