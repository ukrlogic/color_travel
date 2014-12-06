<?php

namespace Application\UkrLogic\MainBundle\Controller;

use Application\UkrLogic\MainBundle\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * @Route("/page/{name}", name="page")
     * @Template()
     */
    public function indexAction($name)
    {
        $page = $this->getDoctrine()->getRepository('ApplicationUkrLogicMainBundle:Page')->findOneBy(['name' => $name]);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        return [
            'page' => $page,
        ];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction(Request $request)
    {
        $feedBack = new Feedback();
        $form = $this->createForm('application_ukrlogic_mainbundle_feedback', $feedBack);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($feedBack);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($request->getRequestUri());
        }

        return [
            'form' => $form->createView()
        ];
    }

}
