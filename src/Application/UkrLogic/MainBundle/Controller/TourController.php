<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\TourBundle\Service\FilterOption;
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
        $busForm = $this->createForm('bus_tour_filter');

        $busForm->handleRequest($request);

        if ($busForm->isValid()) {
            $tours = $this->get('application_ukr_logic_tourbundle.tour_repository')->filterBy(new FilterOption([
                'countries' => $busForm->get('countries')->getData(),
                'date_from' => $busForm->get('date')->getData()['date_from'],
                'date_to' => $busForm->get('date')->getData()['date_to'],
                'days_from' => $busForm->get('days')->getData()['days_from'],
                'days_to' => $busForm->get('days')->getData()['days_to'],
                'price_from' => $busForm->get('price')->getData()['price_from'],
                'price_to' => $busForm->get('price')->getData()['price_to'],
            ]));

            return [
                'bus_form' => $busForm->createView(),
                'tours' => $tours,
            ];
        }


        return [
            'bus_form' => $busForm->createView()
        ];
    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $this->get('application_ukrlogic_tourbundle.service.tourparser')->parse();

        return new Response();
    }
}
