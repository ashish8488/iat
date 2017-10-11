<?php
namespace IATBundle\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    protected $security;

    protected $session;

    protected $router;

    protected $forntEndRedirectUrl;

    public function __construct(SecurityContext $security, Session $session, Router $router)
    {
        $this->security = $security;
        $this->session = $session;
        $this->router = $router;
        //$this->token_storage = $token_storage;TokenStorageInterface $token_storage
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
        $locale = $request->getLocale();
        setcookie('userdata', '', 0, "/");

        if ($request->get('remember_me')==1) {
            $userCookieData['username'] = $request->get('_username');
            $userCookieData['password'] = $request->get('_password');

            setcookie('userdata', serialize($userCookieData), time() + (86400 * 365), "/");
        }

       // $this->token_storage->getToken()->setAuthenticated(count($roles) > 0);
       // print_r( $this->token_storage->getToken()->getRoles() ); die;

        if ($request->isXmlHttpRequest()) {
            $response['success_message'] = 'SUCCESS';
             
            /* if(preg_match('/^office_brain_userbundle_login_/', $this->commonLibraryManager->getRefererRouteName()) || preg_match('/^office_brain_userbundle_sign_up_/', $this->commonLibraryManager->getRefererRouteName())) { 
            	$response['reload_page'] = 'true';
            }
            else {
            	$response['reload_user_transaction'] = 'true';
            } */
            
            return new JsonResponse($response);
        } else {
            $url = $this->router->generate('office_brain_page_homepage');
            return new RedirectResponse($url);
        }
    }
}
