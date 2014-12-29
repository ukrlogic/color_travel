<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\MainBundle\Entity\Comment;
use Application\UkrLogic\MainBundle\Entity\Favorite;
use Application\UkrLogic\MainBundle\Entity\History;
use Application\UkrLogic\MainBundle\Entity\OrderTour;
use Application\UkrLogic\MainBundle\Form\OrderTourType;
use Application\UkrLogic\TourBundle\Entity\AviaTour;
use Application\UkrLogic\TourBundle\Entity\Hotel;
use Doctrine\ORM\Query;
use Sonata\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $form = $this->createForm('tour_form', null, ['method' => 'GET']);

        $form->handleRequest($request);

        $tours = $this->get('application_ukrlogic_tourbundle.service.tourrepository')
            ->find($form, $this->get('service_container')->getParameter('tours_for_page'));

        if ($request->isXmlHttpRequest()) {

            return new JsonResponse($this->renderView(
                'ApplicationUkrLogicMainBundle:Tour:tiles.html.twig', [
                    'tours' => $tours,
                ]
            ));
        }

        return [
            'form'  => $form->createView(),
            'tours' => $tours,
        ];
    }

    /**
     * @Route("/tour/avia/{id}", name="avia_tour")
     * @Template()
     */
    public function aviaTourAction($id, Request $request)
    {
        $order = new OrderTour();
        $user = $this->getUser();

        if ($user instanceof User) {
            $order->setFio($user->getUsername());
            $order->setPhone($user->getPhone());
            $order->setEmail($user->getEmail());
            $order->setInfo($this->generateUrl('avia_tour', ['id' => $id], true));
            $order->setStatus(false);
        }

        $form = $this->createForm(new OrderTourType(), $order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Заявка на тур подана успешно, ожидайте пока с Вами свяжется наш мененджер');

            return $this->redirect($request->getRequestUri());
        }

        $lastSearch = $this->get('session')->get('lastSearch');

        if (!$lastSearch || !array_key_exists($id, $lastSearch)) {
            throw new NotFoundHttpException("Tour not found");
        }

        $tour = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:AviaTour')->findOneBy(['tourId' => $id]);

        if (!$tour) {
            $tour = new AviaTour();
            $tour->setTourId($id)->setData($lastSearch[$id]);

            $this->getDoctrine()->getManager()->persist($tour);
            $this->getDoctrine()->getManager()->flush();
        }

        $hotel = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicTourBundle:Hotel')
            ->find(
                $tour->getData()['Allocation']['id']
            );

//        $description = $hotel ? $hotel->getFullDescription() : '';
        $description = '';

        $this->saveToHistory($id, 'avia');

        return [
            'tour'        => $tour->getData(),
            'inFavorite'  => $this->in('ApplicationUkrLogicMainBundle:Favorite', $id, 'avia'),
            'description' => $description,
            'form'        => $form->createView(),
        ];
    }

    /**
     * @Route("/tour/bus/{id}", name="bus_tour")
     * @Template()
     */
    public function busTourAction($id, Request $request)
    {
        $order = new OrderTour();
        $user = $this->getUser();

        if ($user instanceof User) {
            $order->setFio($user->getUsername());
            $order->setPhone($user->getPhone());
            $order->setEmail($user->getEmail());
            $order->setInfo($this->generateUrl('bus_tour', ['id' => $id], true));
            $order->setStatus(false);
        }

        $form = $this->createForm(new OrderTourType(), $order);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($order);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Заявка на тур подана успешно, ожидайте пока с Вами свяжется наш мененджер');

            return $this->redirect($request->getRequestUri());
        }

        $tourEntity = $this->getDoctrine()->getRepository('ApplicationUkrLogicTourBundle:BusTour')->find($id);

        if (!$tourEntity) {
            throw new NotFoundHttpException("Tour not found");
        }

        $resp = $this->get('guzzle.akkord_tour_bus')->getCommand('get_tour', ['id' => $tourEntity->getTourId()])->execute();
        $xml = simplexml_load_string($resp->asXML(), "SimpleXMLElement", LIBXML_NOCDATA);
        $tour = json_decode(json_encode((array)$xml, true));

        $this->saveToHistory($id, 'bus');

        $return = [
            'tour'       => $tour->tour,
            'inFavorite' => $this->in('ApplicationUkrLogicMainBundle:Favorite', $id, 'bus'),
            'form'       => $form->createView(),
            'tourEntity' => $tourEntity
        ];

        return $return;
    }


    public function in($entityName, $id, $type)
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        }

        $entity = $this->getDoctrine()
            ->getRepository($entityName)
            ->findOneBy(
                [
                    'tourId'   => $id,
                    'tourType' => $type,
                    'user'     => $this->getUser(),
                ]
            );

        return $entity === null ? false : true;
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

            $this->get('doctrine.orm.entity_manager')
                ->persist($fave);
            $this->get('doctrine.orm.entity_manager')
                ->flush();
        }

        return $this->redirect(
            $this->get('router')
                ->generate($type . '_tour', ['id' => $id])
        );
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

            $this->getDoctrine()
                ->getManager()
                ->persist($comment);
            $this->getDoctrine()
                ->getManager()
                ->flush();

            $form = $this->createForm('application_ukrlogic_mainbundle_comment', new Comment());
        }

        $comments = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicMainBundle:Comment')
            ->findBy(
                [
                    'tourType' => $tour_type,
                    'tourId'   => $tour_id
                ]
            );

        return [
            'form'     => $form->createView(),
            'comments' => $comments,
        ];
    }

    /**
     * @Route("/parse")
     */
    public function parseAction()
    {
        $this->get('application_ukrlogic_akkordtourbundle.service.tourparser')
            ->loadTours();

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

            $this->get('doctrine.orm.entity_manager')
                ->persist($history);
            $this->get('doctrine.orm.entity_manager')
                ->flush();
        }
    }

    /**
     * @Route("/get_hotels", name="get_hotels", options={"expose"=true}, condition="request.headers.get('X-Requested-With') == 'XMLHttpRequest'")
     */
    public function getHotelsByNameAction(Request $request)
    {
        $findString = $request->get('term');

        $qb = $this->get('doctrine.orm.entity_manager')
            ->createQueryBuilder();

        $hotels = $qb->select('hotel.name, hotel.id')
            ->from('ApplicationUkrLogicTourBundle:Hotel', 'hotel')
            ->where(
                $qb->expr()
                    ->like('hotel.name', ':name')
            )
            ->setParameter('name', "%$findString%")
            ->orderBy('hotel.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(array_column($hotels, 'name', 'id'));
    }

}
