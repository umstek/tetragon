<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render(':default:index.html.twig');
    }

    /**
     * @Route("/ajax")
     */
    public function ajaxAction(Request $request)
    {
        return $this->render(":default:ajaxHelper.html.twig");
    }

    /**
 * @Route("/ajax/{data}-{segment}")
 */
    public function ajaxViewAction(Request $request, $data, $segment)
    {
        return $this->render(":default:ajaxWiew.xml.twig", [
            'data' => $data,
            'segment' => $segment
        ]);
    }

    /**
     * @Route("/sales", name="sales page")
     */
    public function salesAction(Request $request)
    {
        return $this->render(':default:sales.html.twig');
    }

    /**
     * @Route("/admin", name="admin page")
     */
    public function adminAction(Request $request)
    {
        return $this->render(":default:admin.html.twig");
    }

    /**
     * @Route("/repairing", name="repairing page")
     */
    public function repairingAction(Request $request)
    {
        return $this->render(":default:repairing.html.twig");
    }
}
