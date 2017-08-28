<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\TopicComment;
use AppBundle\Entity\User;
use AppBundle\Events;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class TopicSubscriber implements EventSubscriberInterface
{
	private $mailer;
	private $em;
	private $templating;
	private $sender;

	public function __construct(\Swift_Mailer $mailer, EntityManager $em, TwigEngine $templating, $sender)
	{
		$this->mailer = $mailer;
		$this->em = $em;
		$this->templating = $templating;
		$this->sender = $sender;
	}

	public static function getSubscribedEvents()
	{
		return [
			Events::TOPIC_COMMENT_CREATED => 'onCommentCreated'
		];
	}

	public function onCommentCreated(GenericEvent $event)
	{
		$comment = $event->getSubject();
		$user = $comment->getUser();
		$topicId = $comment->getTopic()->getId();

		$users = $this->em->getRepository('AppBundle:User')->getUsersByTopic($topicId);

		foreach ($users as $receiver) {

			if ($receiver->getId() == $user->getId()) {
				continue;
			}

			$this->sendEmailCommentCreated($receiver, $comment);
		}
	}

	/**
	 * Notifies an user that comment has been added
	 * @param User $user            User that will receive notification
	 * @param TopicComment $comment Added comment
	 */
	protected function sendEmailCommentCreated(User $user, TopicComment $comment)
	{
		$subject =  sprintf('[Symfony Forum] %s commented %s',
			$comment->getUser()->getUsername(),
			$comment->getTopic()->getTitle()
		);

		$message = $this->templating->render('Emails/comment_created.html.twig', [
			'comment' => $comment
		]);

		$message = (new \Swift_Message($subject))
			->setFrom($this->sender)
			->setTo($user->getEmail())
			->setBody($message, 'text/html');

		$this->mailer->send($message);
	}
}