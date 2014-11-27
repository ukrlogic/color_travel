<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TourController
 * @package Application\UkrLogic\MainBundle\Controller
 */
class TourController extends Controller
{
    /**
     * @Route("/tours", name="tours_search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        return [];
    }


    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $tours = $this->get('application_ukrlogic_tourbundle.service.tourrepository')->getTours();

        return new Response();

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="test.xml"');

        $response->setContent($content);
        return $response;
    }
}
