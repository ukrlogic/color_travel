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
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{
    /**
     * @Route("/upload/hotels/{id}", name="upload_hotels", requirements={"id" = "\d+"})
     */
    public function indexAction($id)
    {
        $this->get('punk_ave.file_uploader')->handleFileUpload([
            'folder' => "../images/hotels/".$id
        ]);

//        $finder = new Finder();
//        $kernelRootDir = $this->get('service_container')->getParameter('kernel.root_dir');
//        $uploadDir = sprintf("%s/../web/uploads/tmp/attachments/%d/large", $kernelRootDir, $id);
//        /** @var SplFileInfo $file */
//        foreach($finder->files()->in($uploadDir) as $file) {
//            rename($uploadDir . '/' .$file->getRelativePathname(), sprintf("%s/../web/images/hotels/%d/%s", $kernelRootDir, $id, $file->getRelativePathname()));
//        }
    }
} 