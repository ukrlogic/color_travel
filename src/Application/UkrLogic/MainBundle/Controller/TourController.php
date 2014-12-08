<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\MainBundle\Entity\Comment;
use Application\UkrLogic\MainBundle\Entity\Favorite;
use Application\UkrLogic\MainBundle\Entity\History;
use Application\UkrLogic\MainBundle\Form\CommentType;
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
use Symfony\Component\Security\Core\User\UserInterface;

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

        if ($request->get('country') && null === $data) {
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

        if (!$lastSearch ||!array_key_exists($id, $lastSearch)) {
            throw new NotFoundHttpException("Tour not found");
        }

        $tour = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:AviaTour')->findOneBy(['tourId' => $id]);

        if (! $tour) {
            $tour = new AviaTour();
            $tour->setTourId($id)->setData($lastSearch[$id]);

            $this->getDoctrine()->getManager()->persist($tour);
            $this->getDoctrine()->getManager()->flush();
        }

        $hotel = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:Hotel')->find($tour->getData()['Allocation']['id']);

//        $description = $hotel ? $hotel->getFullDescription() : '';
        $description = '';

        $this->saveToHistory($id, 'avia');

        return [
            'tour' => $tour->getData(),
            'inFavorite' => $this->in('ApplicationUkrLogicMainBundle:Favorite', $id, 'avia'),
            'description' => $description,
        ];
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

        $this->saveToHistory($id, 'bus');

        return ['tour' => $tour->tour, 'inFavorite' => $this->in('ApplicationUkrLogicMainBundle:Favorite', $id, 'bus')];
    }


    public function in($entityName, $id, $type)
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        $entity = $this->getDoctrine()->getRepository($entityName)->findOneBy([
            'tourId' => $id,
            'tourType' => $type,
            'user' => $this->getUser(),
        ]);

        return boolval($entity);
    }

    /**
     * @Route("/fave/{type}/{id}", name="fave_tour")
     */
    public function faveAction($id, $type, Request $request)
    {
        if (!$this->in('ApplicationUkrLogicMainBundle:Favorite', $id, $type)) {
            $fave = new Favorite();
            $fave->setTourId($id);
            $fave->setTourType($type);
            $fave->setUser($this->getUser());

            $this->get('doctrine.orm.entity_manager')->persist($fave);
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        return $this->redirect($this->get('router')->generate($type . '_tour', ['id' => $id]));
    }

    /**
     * @Route("/comments/{tour_type}/{tour_id}", name="comments")
     * @Template()
     */
    public function commentAction(Request $request, $tour_type, $tour_id)
    {
        $comment = new Comment();

        $form = $this->createForm('application_ukrlogic_mainbundle_comment', $comment);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setTourId($tour_id);
            $comment->setTourType($tour_type);

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            $form = $this->createForm('application_ukrlogic_mainbundle_comment', new Comment());
        }

        $comments = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:Comment')->findBy([
            'tourType' => $tour_type,
            'tourId' => $tour_id
        ]);

        return [
            'form' => $form->createView(),
            'comments' => $comments,
        ];
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

    public function saveToHistory($id, $type)
    {
        if (!$this->in('ApplicationUkrLogicMainBundle:History', $id, $type)) {
            $history = new History();
            $history->setTourId($id);
            $history->setTourType($type);
            $history->setUser($this->getUser());

            $this->get('doctrine.orm.entity_manager')->persist($history);
            $this->get('doctrine.orm.entity_manager')->flush();
        }
    }

}
