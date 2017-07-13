<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
	private $router;
	private $encoder;
	private $failMessage = 'Invalid username and/or password.';

	public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder)
	{
		$this->router = $router;
		$this->encoder = $encoder;
	}

	protected function getLoginUrl()
	{
		return $this->router->generate('login');
	}

	public function getCredentials(Request $request)
	{
		if ($request->getPathInfo() != '/login' || $request->getMethod() !== 'POST')
		{
			return;
		}

		$username = $request->request->get('username');
		$request->getSession()->set(Security::LAST_USERNAME, $username);
		$password = $request->request->get('password');

		return ['username' => $username, 'password' => $password];
	}

	public function getUser( $credentials, UserProviderInterface $userProvider )
	{
		try
		{
			return $userProvider->loadUserByUsername($credentials['username']);
		}
		catch (UsernameNotFoundException $e)
		{
			throw new CustomUserMessageAuthenticationException($this->failMessage);
		}
	}

	public function checkCredentials( $credentials, UserInterface $user )
	{
		$plainPassword = $credentials['password'];

		if ($this->encoder->isPasswordValid($user, $plainPassword))
		{
			return true;
		}

		throw new CustomUserMessageAuthenticationException($this->failMessage);
	}

	public function onAuthenticationSuccess( Request $request, TokenInterface $token, $providerKey )
	{
		return new RedirectResponse($this->router->generate('homepage'));
	}

	public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
	{
		$request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

		return new RedirectResponse($this->router->generate('login'));
	}

	public function supportsRememberMe()
	{
		return false;
	}
}