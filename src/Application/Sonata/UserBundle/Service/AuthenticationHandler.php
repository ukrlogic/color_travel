<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 06.12.14
 * Time: 22:10
 */

namespace Application\Sonata\UserBundle\Service;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class AuthenticationHandler
 * @package Application\Sonata\UserBundle\Service
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new JsonResponse(['redirect' => $this->router->generate('main_page')]);
    }

} 