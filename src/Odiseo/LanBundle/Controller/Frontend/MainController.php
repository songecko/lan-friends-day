<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller {

	
	public function indexAction() {
		return $this->render ( 'OdiseoLanBundle:Frontend/Main:index.html.twig');
	}
	
	public function renderContentAction(Request $request) {
		$user = $this->getUser();
		if ($user == null) {
			return $this->render ( 'OdiseoLanBundle:Frontend/Main:participate.html.twig');
		} else
		 {
		 	$userRecord = $this->retrieveUserFromDb( $user->getTwitterId ());
			if ( $userRecord->getDni() != null) 
			{	
					return $this->render ( 'OdiseoLanBundle:Frontend/Main:alreadyParticipating.html.twig',	
						array ( 'fullName' => $userRecord->getFullName(),
					    'dni' =>  $userRecord->getDni(),
					    'edad' => $userRecord->getEdad(),
					    'telefono' => $userRecord->getTelefono(),
					   'nacionalidad' =>$userRecord->getNacionalidad(),
					    'email' => $userRecord->getMail() ) );
			} 
			else {
				return $this->render ( 'OdiseoLanBundle:Frontend/Main:registerForm.html.twig');
			}
		}
	}
	
	
	public function registerAction(Request $request) {
		
		$register = $request->get ( 'register' );
		$this->saveUser($register);
		return $this->forward ( 'OdiseoLanBundle:Frontend/Main:index' );
	}
	
	private function saveUser($register){
		$user = $this->getUser ();
		$userRecord = $this->retrieveUserFromDb( $user->getTwitterId ());
		$this->setUserProperties($userRecord , $register);
		$this->getDoctrine ()->getManager ()->flush();
	}
	
	private function retrieveUserFromDb($twitterId){
		
		$em = $this->getDoctrine ()->getManager ();
		$repository = $em->getRepository('OdiseoLanBundle:User' );
		$userRecord = $repository->findOneBy ( array ('twitter_id' => $twitterId ) );
		return $userRecord;
	}
	
	private function setUserProperties($userRecord , $register){
		$userRecord->setFullName ( $register ['fullname'] );
		$userRecord->setDni ( $register ['dni'] );
		$userRecord->setEdad ( $register ['edad'] );
		$userRecord->setTelefono ( $register ['telefono'] );
		$userRecord->setNacionalidad ( $register ['nacionalidad'] );
		$userRecord->setMail ( $register ['mail'] );
	}
	
	
}
