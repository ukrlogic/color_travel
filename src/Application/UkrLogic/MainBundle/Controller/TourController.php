<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\MainBundle\Form\HotelsType;
use Application\UkrLogic\TourBundle\Entity\AviaTour;
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
     * @Route("/tours/", name="tours_search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm('tour_form');
        $form->handleRequest($request);
        $data = $form->getData();
        $page = $request->get('page') ?: 1;
        $limit = 20;

        if ($request->get('country')) {
            $data['countries'] = [$request->get('country') => true];
        }

        $countryEntities = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:Country')->findAll();
        $countries = [];

        foreach ($countryEntities as $countryEntity) {
            $countries[$countryEntity->getId()] = $countryEntity;
        }

        $tours = [];

        if ($form->isSubmitted()) {
            $type = $data['is_avia'] ? 'avia' : 'bus';
            $limit *= 2;
        } else {
            $type = 'combined';

//            $hotels = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:Hotel')->findBy(['country' => $countries[$country]]);
//            $form->add('hotels', new HotelsType($hotels), ['required' => false]);
        }

        if (null === $data) {
            $data = [];
        }


        if ($type === 'combined' || $type === 'bus') {
            $bus = $this->get('application_ukr_logic_tourbundle.tour_repository')->filterBy(new FilterOption($data), $page, $limit);

            foreach ($bus as $tour) {
                $tours[] = [
                    'type' => 'bus',
                    'info' => $tour
                ];
            }
        }

        if ($type === 'combined' || $type === 'avia') {
            $avia = $this->get('application_ukrlogic_tourclientbundle.service.aviatours')->loadTours(new FilterOption($data), $page, $limit);

            $lastSearch = [];

            for ($i = 0; $i < count($avia->Tours->Tour); $i++) {
                $tours[] = [
                    'type' => 'avia',
                    'info' => $avia->Tours->Tour[$i]
                ];

                $lastSearch[(string)$avia->Tours->Tour[$i]->id] = $avia->Tours->Tour[$i];
            }

            $this->get('session')->set('lastSearch', $array = json_decode(json_encode((array)$lastSearch), TRUE));
        }

        if ($type === 'combined') {
            shuffle($tours);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('ApplicationUkrLogicMainBundle:Tour:tiles.html.twig', [
                'tours' => $tours,
                'countries' => $countries,
            ]);
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

        if (!array_key_exists($id, $lastSearch)) {
            throw new NotFoundHttpException("Tour not found");
        }

        $tour = new AviaTour();
        $tour->setTourId($id)->setData($lastSearch[$id]);

        $this->getDoctrine()->getManager()->persist($tour);
        $this->getDoctrine()->getManager()->flush();

        return ['tour' => $tour->getData()];
    }

    /**
     * @Route("/tour/bus/{id}", name="bus_tour")
     * @Template()
     */
    public function busTourAction($id)
    {
        $resp = $this->get('guzzle.akkord_tour_bus')->getCommand('get_tour', ['id' => $id])->execute();
        $xml = simplexml_load_string($resp->asXML(), "SimpleXMLElement", LIBXML_NOCDATA);
        $tour = json_decode(json_encode((array)$xml, true));

        if (!$tour) {
            throw new NotFoundHttpException("Tour not found");
        }

        return ['tour' => $tour->tour];
    }

    public function commentAction()
    {

    }

    /**
     * @Route("/parse")
     */
    public function parseAction()
    {
        $this->get('application_ukrlogic_tourbundle.service.tourparser')->parse();

        return new Response();
    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {

        return new Response();
    }

}
