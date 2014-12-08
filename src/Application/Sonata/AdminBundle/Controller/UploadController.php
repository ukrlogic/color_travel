<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 03.12.14
 * Time: 7:33
 */

namespace Application\Sonata\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    /**
     * @Route("/upload/hotels/{id}", name="upload_hotels", requirements={"id" = "\d+"})
     */
    public function hotelsAction($id)
    {
        $this->get('punk_ave.file_uploader')->handleFileUpload([
            'folder' => "../images/hotels/".$id
        ]);
    }

    /**
     * @Route("/upload/countries/{id}", name="upload_countries", requirements={"id" = "\d+"})
     */
    public function countryAction($id)
    {
        $this->get('punk_ave.file_uploader')->handleFileUpload([
            'folder' => "../images/countries/".$id
        ]);
    }

    /**
     * @Route("/upload/remove", name="remove_image")
     */
    public function deleteAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN') === false) {
            return new JsonResponse(['error' => 'Permission denied']);
        }

        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $path = $this->get('service_container')->getParameter('kernel.root_dir') . '/../web' . $request->get('path');

            if (file_exists($path)) {
                unlink($path);
            } else {
                return new JsonResponse(['error' => 'File does not exists', 'path' => $path]);
            }

            return new JsonResponse(['success' => 'ok']);
        }

        return new JsonResponse(['error' => 'Bad request']);
    }
} 