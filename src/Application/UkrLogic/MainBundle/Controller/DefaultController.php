<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Application\UkrLogic\TourBundle\Entity\AviaTour;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
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
     * @Route("/profile/news", name="profile")
     * @Template()
     */
    public function profileAction()
    {
        return $this->getProfileNewsPageContent();
    }

    /**
     * @Route("/profile/")
     */
    public function profileRedirectAction()
    {
        return $this->redirect($this->generateUrl('profile'));
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     * @Template()
     */
    public function profileEditAction(Request $request)
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            return new RedirectResponse($this->generateUrl('main_page'));
        }

        $form = $this->createForm('application_sonata_userbundle_user', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User|null $userWithIdenticalEmail */
            $userWithIdenticalEmail = $this->getDoctrine()->getRepository('ApplicationSonataUserBundle:User')->findOneBy(['email' => $user->getEmail()]);

            if (null !== $userWithIdenticalEmail && $user->getId() !== $userWithIdenticalEmail->getId()) {
                $form->addError(new FormError('Пользователь с таким E-mail уже существует!'));

                return array_merge(['form' => $form->createView()], $this->getProfileNewsPageContent());
            }

            /** @var User|null $userWithIdenticalUsername */
            $userWithIdenticalUsername = $this->getDoctrine()->getRepository('ApplicationSonataUserBundle:User')->findOneBy(['username' => $user->getUsername()]);

            if (null !== $userWithIdenticalUsername && $user->getId() !== $userWithIdenticalUsername->getId()) {
                $form->addError(new FormError('Пользователь с таким именем уже существует!'));

                return array_merge(['form' => $form->createView()], $this->getProfileNewsPageContent());
            }

            $user->upload();

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('profile'));
        }

        return array_merge(['form' => $form->createView()], $this->getProfileNewsPageContent());
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

        $historyTours = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicMainBundle:History')
            ->findBy(['user' => $user]);
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

        $favoritesTours = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicMainBundle:Favorite')
            ->findBy(['user' => $user]);

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
                $qb->select('t')
                    ->from('ApplicationUkrLogicTourBundle:BusTour', 't')
                    ->where(
                        $qb->expr()
                            ->in('t.tourId', $ids)
                    );

                foreach ($qb->getQuery()
                             ->getResult() as $tour) {
                    $tours[] = [
                        'type' => 'bus',
                        'info' => $tour
                    ];
                }

                break;
            case 'avia':
                $qb = $em->createQueryBuilder('t');
                $qb->select('t')
                    ->from('ApplicationUkrLogicTourBundle:AviaTour', 't')
                    ->where(
                        $qb->expr()
                            ->in('t.tourId', $ids)
                    );

                /** @var AviaTour $tour */
                foreach ($qb->getQuery()
                             ->getResult() as $tour) {
                    $tours[] = [
                        'type' => 'avia',
                        'info' => $tour->getData()
                    ];
                }

                break;
            default:
                $tours = [];
                break;
        }


        return $tours;
    }

    /**
     * @return array
     */
    public function getProfileNewsPageContent()
    {
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            return new RedirectResponse($this->generateUrl('main_page'));
        }

        $commentsTours = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicMainBundle:Comment')
            ->findBy(['user' => $user]);
        $params = [];

        foreach ($commentsTours as $commentTour) {
            $params[$commentTour->getTourType()][$commentTour->getTourId()] = $commentTour->getTourId();
        }

        $tours = $this->getTours($params);

        $news = $this->getDoctrine()
            ->getRepository('ApplicationUkrLogicMainBundle:Post')
            ->findAll();

        $paginator = $this->get('knp_paginator')->paginate($news, $this->get('request')->query->get('page'), 6);

        return [
            'commentsTours' => $tours,
            'news'          => $paginator,
        ];
    }

    /**
     * @Route("/avia-tickets", name="avia_tickets")
     * @Template
     */
    public function aviaTicketsAction()
    {
        return [];
    }
}
