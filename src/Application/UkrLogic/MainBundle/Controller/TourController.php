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
        $busForm = $this->createForm('bus_tour_filter');

        $busForm->handleRequest($request);

        if ($busForm->isValid()) {
            $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
            $qb->select('t')->from('ApplicationUkrLogicTourBundle:BusTour', 't');

            foreach ($busForm->get('countries')->getData() as $code => $value) {
                if ($value) {
                    $qb->join('t.Countries', 'c')->where('c.alpha2 = :country')->setParameter('country', $code);
                    break;
                }
            }

            if ($days = $busForm->get('days')->getData()) {
                $qb->where('t.days BETWEEN :days_from AND :days_to')
                    ->setParameter('days_from', $days['days_from'])
                    ->setParameter('days_to', $days['days_to']);
            }
            if ($days = $busForm->get('date')->getData()) {
                $qb->where('t.days BETWEEN :date_from AND :days_to')
                    ->setParameter('days_from', $days['days_from'])
                    ->setParameter('days_to', $days['days_to']);
            }
            if ($days = $busForm->get('price')->getData()) {
                $qb->where('t.price_uah BETWEEN :price_from AND :price_to')
                    ->setParameter('price_from', $days['price_from'])
                    ->setParameter('price_to', $days['price_to']);
            }
        }
        die;

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
