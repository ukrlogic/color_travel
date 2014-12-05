<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\TourBundle\Entity\City;
use Application\UkrLogic\TourBundle\Entity\Country;
use Application\UkrLogic\TourBundle\Entity\Resort;
use Application\UkrLogic\TourBundle\Service\FilterOption;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $form = $this->createForm('tour_form');

        $form->handleRequest($request);

        $tours = [];

        if ($form->isValid()) {
            $data = $form->getData();
            $type = $data['is_avia'] ? 'avia' : 'bus';
        } else {
            $data = [];
            $type = 'combined';
        }

        $countryEntities = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:Country')->findAll();
        $countries = [];

        foreach ($countryEntities as $country) {
            $countries[$country->getId()] = $country;
        }

        if ($type === 'combined' || $type === 'bus') {
            $bus = $this->get('application_ukr_logic_tourbundle.tour_repository')->filterBy(new FilterOption($data));

            foreach ($bus as $tour) {
                $tours[] = [
                    'type' => 'bus',
                    'info' => $tour
                ];
            }
        }

        if ($type === 'combined' || $type === 'avia') {
            $avia = $this->get('application_ukrlogic_tourclientbundle.service.aviatours')->loadTours(new FilterOption($data));

            $lastSearch = [];

            for ($i = 0; $i < count($avia->Tours->Tour); $i++) {
                $tours[] = [
                    'type' => 'avia',
                    'info' => $avia->Tours->Tour[$i]
                ];

                $lastSearch[intval($avia->Tours->Tour[$i]->id)] = $avia->Tours->Tour[$i];
            }

            $this->get('session')->set('lastSearch', $array = json_decode(json_encode((array)$lastSearch), TRUE));
        }

        if ($type === 'combined') {
            shuffle($tours);
        }

        return [
            'form' => $form->createView(),
            'tours' => $tours,
            'countries' => $countries,
            'type' => $type,
        ];
    }

    /**
     * @Route("/tour/avia/{id}", name="avia_tour")
     * @Template()
     */
    public function aviaTourAction($id)
    {
        $lastSearch = $this->get('session')->get('lastSearch');

        $id = intval($id);

        if (! array_key_exists($id, $lastSearch)) {
            throw new NotFoundHttpException("Tour not found");
        }

        return ['tour' => $lastSearch[$id]];
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
