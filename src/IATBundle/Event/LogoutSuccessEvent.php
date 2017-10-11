<?php
namespace iATBundle\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LogoutSuccessEvent implements LogoutSuccessHandlerInterface
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
        //$user = $this->security->getToken()->getUser();
        //add code to handle $user here
        //...
        /*
        $locale=$request->query->get('_locale', '');
        $responseURL='/';
        if ($locale=='fr') {
            $responseURL='/fr';
        }
        $response = new RedirectResponse($responseURL);
        foreach ($_COOKIE as $cookieKey=>$value) {
            if (preg_match('/^wordpress/', $cookieKey)) {
                $response->headers->clearCookie($cookieKey);
            }
        }

        return $response;
        */

    	return new RedirectResponse($this->router->generate('office_brain_page_homepage_view'));
    }
}
