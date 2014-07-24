<?php

namespace Odiseo\LanBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Odiseo\LanBundle\Entity\User as User;

class SendMailer
{
	
	private $message;
	private $container;
	
	public function __construct(Container $container){
		$this->message = \Swift_Message::newInstance();
		$this->container = $container;
	}

	public function sendRegisterMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:registerEmail.html.twig';
		
		$message = $this->getMessage($view, $email)
			->setSubject($fullname.', ya estás registrado en la app del Mes del Amigo LAN!');
		
		$failures = $this->send($message);
		
		return $failures;
	}
	
	public function sendBeginMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:beginEmail.html.twig';
		
		$message = $this->getMessage($view, $email)
						->setSubject($fullname.', ya ha comenzado la promoción!');
		
		$failures = $this->send($message);
		
		return $failures;
	}
	
	public function sendEndMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:endEmail.html.twig';
		
		$message = $this->getMessage($view, $email)
			->setSubject($fullname.', ya ha finalizado la promoción!');
		
		$failures = $this->send($message);
		
		return $failures;
	}
	
	public function sendRemainderMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:remainderEmail.html.twig';
	
		$message = $this->getMessage($view, $email)
			->setSubject('¡A las 16hs finaliza! Queda poco tiempo para subirte al avión.');
	
		$failures = $this->send($message);
	
		return $failures;
	}
	
	protected function send($message)
	{
		$failures = array();
		
		$mailer = $this->container->get('mailer');
		$mailer->send($message, $failures);
		
		// manually flush the queue (because using spool)
		$spool = $mailer->getTransport()->getSpool();
		$transport = $this->container->get('swiftmailer.transport.real');
		$spool->flushQueue($transport);
		
		return $failures;
	}
	
	
	
	private function getMessage($view, $emailTo)
	{
		return $this->message
			->setSubject('Amigos Lan')
			->setFrom(array('noreply@amigoslan.com' => 'Amigos Lan'))
			->setTo($emailTo)
			->setBody(
				$this->container->get('templating')->render($view),
				'text/html'
			);
	}
}