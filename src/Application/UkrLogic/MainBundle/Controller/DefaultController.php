<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function profileAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            return [

            ];
        }

        return new RedirectResponse($this->generateUrl('main_page'));
    }
}
