<?php
namespace IATBundle\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminLogoutSuccessEvent implements LogoutSuccessHandlerInterface
{
    private $security;
    private $router;

    public function __construct(SecurityContext $security, Router $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
    	return new RedirectResponse($this->router->generate('iatbundle_admin_login'));
    }
}
