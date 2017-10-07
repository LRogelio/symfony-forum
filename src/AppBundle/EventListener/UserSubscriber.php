<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Events;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{
	private $mailer;
	private $templating;
	private $sender;


	public function __construct(\Swift_Mailer $mailer, TwigEngine $templating, $sender)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->sender = $sender;
	}

	public static function getSubscribedEvents()
	{
		return [
			Events::USER_CHANGE_PASSWORD => 'onPasswordChanged'
		];
	}

	public function onPasswordChanged(GenericEvent $event)
	{
		$user = $event->getSubject();

		if (!$user instanceof User)
			return;

		$this->sendEmailPasswordChanged($user);
	}

	protected function sendEmailPasswordChanged(User $user)
	{
		$subject = '[Symfony Forum] Your password has been changed';
		$message = $this->templating->render('Emails/password_changed.html.twig', [
			'user' => $user
		]);

		$message = (new \Swift_Message($subject))
			->setFrom($this->sender)
			->setTo($user->getEmail())
			->setBody($message, 'text/html');

		$this->mailer->send($message);
	}
}