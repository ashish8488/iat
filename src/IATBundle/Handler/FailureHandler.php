<?php
namespace IATBundle\Handler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;

class FailureHandler extends DefaultAuthenticationFailureHandler
{
    public function __construct(HttpKernelInterface $httpKernel, HttpUtils $httpUtils, array $options, LoggerInterface $logger = null)
    {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $exception_json = '';

        if (get_class($exception) === 'Symfony\Component\Security\Core\Exception\LockedException') {
            $exception = new \Symfony\Component\Security\Core\Exception\LockedException("NOT_APPROVE");
            $exception_json = 'NOT_APPROVE';
        }
        if (get_class($exception) === 'Symfony\Component\Security\Core\Exception\DisabledException') {
            $exception = new \Symfony\Component\Security\Core\Exception\DisabledException("BLOCKED");
            $exception_json = 'BLOCKED';
        }
        if (get_class($exception)==='Symfony\Component\Security\Core\Exception\BadCredentialsException') {
            $exception = new \Symfony\Component\Security\Core\Exception\BadCredentialsException("INVALID");
            $exception_json = 'INVALID';
        }
        if (get_class($exception) === 'Symfony\Component\Security\Core\Exception\AccountExpiredException') {
            $exception = new \Symfony\Component\Security\Core\Exception\AccountExpiredException("EXPIRED");
            $exception_json = 'EXPIRED';
        }

        if ($request->get('_username')=='') {
            $exception = new \Symfony\Component\Security\Core\Exception\BadCredentialsException("ENTER_USERNAME");
            $exception_json = 'ENTER_USERNAME';
        } elseif ($request->get('_password')=='') {
            $exception = new \Symfony\Component\Security\Core\Exception\BadCredentialsException("ENTER_PASSWORD");
            $exception_json = 'ENTER_PASSWORD';
        }

        if ($request->isXmlHttpRequest()) {
            $response['error_message'] = $exception_json;
            return new JsonResponse($response);
        } else {
            return parent::onAuthenticationFailure($request, $exception);
        }
    }
}
