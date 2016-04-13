<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @Route("/customers/", name="customers")
     * @Method({"GET", "HEAD"})
     * @param Request $request
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        // Content-Type that the client accepts
        $accept = AcceptHeader::fromString($request->headers->get('Accept'));

        // Collect customer objects from the database
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Customer');
        $customers = $repository->findAll();

        // A browser? Whatever accepting html
        if ($accept->has('*/*') or $accept->has('text/html')) {
            $customer = new Customer();
            $form = $this->createForm(CustomerType::class, $customer);
            return $this->render(':Customer:index.html.twig', [
                'list' => true, // A flag to be used in the template saying that we are listing customers
                'customers' => $customers,
                'form' => $form->createView()
            ]);
        }

        // JSON Response - if it accepts JSON
        if ($accept->has('application/json') and !$accept->has('*/*')) {
            // To avoid XSSI JSON Hijacking, the returned JSON must be an Object, not an Array.
            $data = ['customers' => []];
            foreach ($customers as $index => $customer) {
                $data['customers'][] = [
                    'id' => $customer->getId(),
                    'name' => $customer->getName(),
                    'address' => $customer->getAddress(),
                    'phone' => $customer->getPhone(),
                    'email' => $customer->getEmail(),
                    'nic' => $customer->getNic()
                ];
            }
            return new JsonResponse($data);
        }

        // Unknown Accept type
        throw new \Exception();
    }

    /**
     * @Route("/customers/", name="add customer")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        // Customer object to hold the collected data
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if ($form->isValid()) { // Validation
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->addFlash('success', "Created customer.");
            return $this->redirectToRoute('customers');
        }

        // Executed only if validation fails
        return $this->render(':Customer:index.html.twig', [
            'list' => false,
            'customers' => [],
            'form' => $form->createView()
        ]);
    }

}
