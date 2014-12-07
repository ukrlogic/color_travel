<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\TourBundle\Entity\AviaTour;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DefaultController
 * @package Application\UkrLogic\MainBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_page")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            return new RedirectResponse($this->generateUrl('main_page'));
        }

        $form = $this->createForm('application_sonata_userbundle_user', $user);
        $form->handleRequest($request);

        $commentsTours = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:Comment')->findBy(['user' => $user]);
        $params = [];

        foreach ($commentsTours as $commentTour) {
            $params[$commentTour->getTourType()][$commentTour->getTourId()] = $commentTour->getTourId();
        }

        $tours = $this->getTours($params);

        $news = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:Post')->findAll();

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->getRequestUri());
        }

        return [
            'commentsTours' => $tours,
            'news' => $news,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/profile/history", name="profile_history")
     * @Template()
     */
    public function profileHistoryAction()
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            return new RedirectResponse($this->generateUrl('main_page'));
        }

        $historyTours = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:History')->findBy(['user' => $user]);
        $params = [];

        foreach ($historyTours as $historyTour) {
            $params[$historyTour->getTourType()][$historyTour->getTourId()] = $historyTour->getTourId();
        }

        $tours = $this->getTours($params);

        return [
            'tours' => $tours,
        ];
    }

    /**
     * @Route("/profile/favorites", name="profile_favorites")
     * @Template()
     */
    public function profileFavoritesAction()
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            return new RedirectResponse($this->generateUrl('main_page'));
        }

        $favoritesTours = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:Favorite')->findBy(['user' => $user]);

        $params = [];

        foreach ($favoritesTours as $favoriteTour) {
            $params[$favoriteTour->getTourType()][$favoriteTour->getTourId()] = $favoriteTour->getTourId();
        }
        $tours = $this->getTours($params);

        return [
            'tours' => $tours,
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function getTours(array $params)
    {
        $tours = [];

        foreach ($params as $type => $ids) {
            $tours = array_merge($tours, $this->getToursByType($type, $ids));
        }

        return $tours;
    }

    /**
     * @param string $type
     * @param array $ids
     * @return array
     */
    public function getToursByType($type, array $ids)
    {
        $tours = [];
        $em = $this->get('doctrine.orm.entity_manager');

        switch ($type) {
            case 'bus':
                $qb = $em->createQueryBuilder('t');
                $qb->select('t')->from('ApplicationUkrLogicTourBundle:BusTour', 't')->where($qb->expr()->in('t.tourId', $ids));

                foreach ($qb->getQuery()->getResult() as $tour) {
                    $tours[] = ['type' => 'avia', 'info' => $tour];
                }

                break;
            case 'avia':
                $qb = $em->createQueryBuilder('t');
                $qb->select('t')->from('ApplicationUkrLogicTourBundle:AviaTour', 't')->where($qb->expr()->in('t.tourId', $ids));

                /** @var AviaTour $tour */
                foreach ($qb->getQuery()->getResult() as $tour) {
                    $tours[] = ['type' => 'avia', 'info' => $tour->getData()];
                }

                break;
            default:
                $tours = [];
                break;
        }


        return $tours;
    }
}
