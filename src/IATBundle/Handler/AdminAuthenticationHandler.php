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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminAuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    protected $security;
    protected $session;
    protected $router;

    public function __construct(SecurityContext $security, Session $session, Router $router)
    {
        $this->security = $security;
        $this->session = $session;
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
        $locale = $request->getLocale();
        setcookie('adminuserdata', '', 0, "/");

        if ($request->get('remember_me')==1) {
            $userCookieData['username'] = $request->get('_username');
            $userCookieData['password'] = $request->get('_password');

            setcookie('adminuserdata', serialize($userCookieData), time() + (86400 * 365), "/");
        }
        
        /* $userType = $this->security->getToken()->getUser()->getType();
        $pathToGenerate = '';
        if(!empty($userType) && $userType == 'admin'){
        	$pathToGenerate = 'iatbundle_admin_registerMerchant';
        }elseif (!empty($userType) && $userType == 'merchant'){
        	$pathToGenerate = 'iatbundle_admin_listInquiry';
        } */
        
        $pathToGenerate = 'iatbundle_admin_dealLinkList';

        $url = $this->router->generate($pathToGenerate);

        return new RedirectResponse($url);
    }
}
