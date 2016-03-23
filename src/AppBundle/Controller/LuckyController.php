<?php
/**
 * Created by PhpStorm.
 * User: Wickramaranga
 * Date: 3/21/2016
 * Time: 10:57 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{count}", name="ln")
     * @param $count
     * @return Response
     */
    public function numberAction($count)
    {
        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = $i;
        }
        $numbersList = implode(', ', $numbers);
        $this->addFlash("notice", "Test message");
        $html = $this->render(
            'lucky/number.html.twig',
            ['luckyNumberList' => $numbersList]
        );
        return new Response($html);
    }
}