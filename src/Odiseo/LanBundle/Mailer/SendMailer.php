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
		
		$this->getMessage($view, $email)
			->setSubject($fullname.', ya estás registrado en la app del Mes del Amigo LAN!');
		
		$this->container->get('mailer')->send($this->message);
	}
	
	public function sendBeginMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:beginEmail.html.twig';
	
		$this->getMessage($view, $email)
			->setSubject($fullname.', ya ha comenzado la promoción!');
		
		$this->container->get('mailer')->send($this->message);
	}
	
	public function sendEndMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:endEmail.html.twig';
	
		$this->getMessage($view, $email)
			->setSubject($fullname.', ya ha finalizado la promoción!');
	
		$this->container->get('mailer')->send($this->message);
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