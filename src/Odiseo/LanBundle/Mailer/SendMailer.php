<?php

namespace Odiseo\LanBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Odiseo\LanBundle\Entity\User as User;

class SendMailer{
	
	private $message;
	private $container;
	private $fullname;
	private $email;
	
	public function __construct(Container $container){
		$this->message = \Swift_Message::newInstance();
		$this->container = $container;
		
	}

	public function SendRegisterMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		
		
		$this->message
		->setSubject('Lan Amigos')
		->setFrom('prueba@test.com')
		->setTo($email)
		->setBody(
				'Hola '.$fullname.', te has registrado satisfactoriamente.'				
		)
		;
		$this->container->get('mailer')->send($this->message);
		
	}
	
	public function SendCampaignMail(User $user)
	{
		$fullname = $user->getFullName();
		$email = $user->getMail();
		$view = 'OdiseoLanBundle:Frontend/Mailer:email.html.twig';
	
		$this->message
		->setSubject('Lan Amigos')
		->setFrom('prueba@test.com')
		->setTo($email)
		->setBody(
				$this->container->get('templating')->render($view, array('fullname' => $fullname)),'text/html'
		)
		;
		$this->container->get('mailer')->send($this->message);
	
	}
}