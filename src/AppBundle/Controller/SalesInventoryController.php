<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SellingItem;
use AppBundle\Form\SellingItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        if ($request->query->count() > 0) { // get request
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
            $items = $repository->findBy(["isSold" => false]);//$repository->findBy(["isSold"=>false]);
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
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $item = new SellingItem();
        $form = $this->createForm(SellingItemType::class, $item); // creating a Selling item type form, with the selling item object
        $form->handleRequest($request); // combine the form`s data into the "$item"  mean time validate the form`s data.
        if ($form->isValid()) { // Validation (at the first iteration form is not valid)
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush(); // Permanently add to database

            $this->addFlash('success', "Created item.");
            return $this->redirectToRoute('new item');
        } else {
            if ($request->isMethod('POST')) {
                $this->addFlash('error', 'Form contains errors. ');
            }
        }

        return $this->render(':SalesInventory:create.html.twig', [ // if form is empty; load this page
            'form' => $form->createView() //'form' is the variable we pass into the twig file //remove('isSold')->remove('isWarrantyClaimed')->remove("warrantyExpiration")->
        ]);
    }

    /**
     * @Route("/selling_items/{id}", name="view item", methods={"GET"}, requirements={"id" : "\d+"})
     *
     * @param $id
     * @return Response
     * Request $request
     */
    public function viewAction($id, Request $request)
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
     * @Route("/selling_items/{id}.edit", name="edit item", methods={"GET", "HEAD"}, requirements={"id" : "\d+"})
     * @Route("/selling_items/{id}", name="update item", methods={"POST"}, requirements={"id" : "\d+"})
     *
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
        } elseif ($item->getIsSold()) {
            $this->addFlash('warning', "You cannot edit an already sold item. ");
            return $this->redirectToRoute('items');
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
            } else {
                $this->addFlash('error', 'Form contains errors. ');
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
        if ($request->request->has('selling_item')) {// Selling item form data//post request // selling_item is an array //if the array has filled
            foreach ($request->request->get('selling_item') as $key => $value) {
                if ($value != null and $value != '' and $key != '_token') { // omit the empty ones and the CSRF token
                    $nonempty[$key] = $value;
                }
            }
        }

        if (count($nonempty) == 0) { // no parameters submitted, meaning asking for the empty form
            if ($request->isMethod('POST')) {
                $this->addFlash('info', 'Please provide some known information. ');
            }
            return $this->render(':SalesInventory:search.html.twig', [
                'form' => $form->remove("price")->remove("description")->remove("warrantyPeriod")->createView() //->  remove("warrantyExpiration")->remove("isWarrantyClaimed")->remove("isSold")->remove("price")->
            ]);
        }
        return $this->redirectToRoute('items', $nonempty);
    }

    /**
     * @Route("/ajax/selling_items.search", name="ajax search selling items by serial", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function searchBySerialAjaxAction(Request $request)
    {

        if ($request->isMethod('POST')) {
            $serial = $request->request->get('serial');
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:SellingItem');
            $items = $repository->findBy(['serial' => $serial, 'isSold' => false]);
            if (count($items) > 0) {
                return $this->render(':SalesInventory:searchAjax.xml.twig', [
                    'items' => $items
                ]);
            } else {
                $this->addFlash('info', "No items found for the query. ");
            }
        }

        return $this->render(':SalesInventory:searchAjax.xml.twig', [
            'items' => null
        ]);
    }

    /**
     * @Route("/selling_items/{id}.claim", name="claim item", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function claimWarrantyAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:SellingItem');
        $item = $repository->find($id);

        if ($request->isMethod('POST')) {
            if ($request->request->has('confirm')
                && $request->request->get('confirm') == 'confirm'
                && $item->getIsSold()
                && !$item->getIsWarrantyClaimed()
                && $item->getWarrantyExpiration() != null
                && ($item->getWarrantyExpiration() > new \DateTime('now', new \DateTimeZone('Asia/Colombo')))
            ) {
                $item->setIsWarrantyClaimed(true);
                $em->flush();
                $this->addFlash('success', "Warranty has successfully been claimed. ");
                return $this->redirectToRoute('view item', ['id' => $id]);
            } else {
                $this->addFlash('info', "You did not confirm the warranty claim. ");
                return $this->redirectToRoute('view item', ['id' => $id]);
            }
        } // else

        if ($item->getIsSold()) {
            if ($item->getIsWarrantyClaimed()) {
                $this->addFlash('warning', "Warranty for this item has already been claimed.");
                return $this->redirectToRoute('view item', ['id' => $id]);
            } else {
                if ($item->getWarrantyExpiration() != null
                    && ($item->getWarrantyExpiration() > new \DateTime('now', new \DateTimeZone('Asia/Colombo')))
                ) {
                    $this->addFlash('info', "A warranty claim is possible for the item.");
                    return $this->render(':SalesInventory:warrantyClaim.html.twig', ['id' => $id]); //TODO
                } else {
                    $this->addFlash('warning', "This item has no warranty or warranty period has been expired.");
                    return $this->redirectToRoute('view item', ['id' => $id]);
                }
            }
        } else {
            $this->addFlash('warning', "This item is not yet sold.");
            return $this->redirectToRoute('view item', ['id' => $id]);
        }
    }
}
