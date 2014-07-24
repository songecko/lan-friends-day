<?php

namespace Odiseo\LanBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('send:mail')
			->setDescription('Send Mail')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{	
		$configuration = null;
		$dm = $this->getContainer()->get('doctrine')->getManager();
		 
		//get the current configuration
		$configurations = $this->getContainer()->get('lan.repository.configuration')->findAll();
		
		if(isset($configurations[0]))
			$configuration = $configurations[0];
		
		if(!$configuration)
		{
			$output->writeln($this->getFormatedMessage("Unable to get the configuration object"));
			return;
		}
		
		$beginMailSended = $configuration->getBeginMailSended();
		$endMailSended = $configuration->getEndMailSended();
		
		//If the campaign is active and the begin email never sended 
		if($configuration->isCampaignActive() && $beginMailSended == false)
		{
			//Send the begin email to all users
			$output->writeln($this->getFormatedMessage("Send BEGIN email to all users."));
			$output->writeln($this->sendEmail(true));
			
			//Save the configuration
			$configuration->setBeginMailSended(true);
			$configuration->setEndMailSended(false);
			$dm->flush();
		}
		//If the campaign is finished and the end email never sended
		else if ($configuration->isCampaignFinished() && $endMailSended == false)
		{
			//Send the end email to all users
			$output->writeln($this->getFormatedMessage("Send END email to all users."));
			$output->writeln($this->sendEmail(false));
			
			//Save the configuration
			$configuration->setEndMailSended(true);
			$configuration->setBeginMailSended(false);
			$dm->flush();
		}else {
			//Nothing to do
			$output->writeln($this->getFormatedMessage("Nothing to do"));
		}
	}
	
	public function sendEmail($isBeginEmail)
	{
		$sendMailer = $this->getContainer()->get('lan.send.mailer');
		$userRepository = $this->getContainer()->get('lan.repository.user');
		
		$registeredUsers = $userRepository->getRegisteredUsers();
		
		$total = count($registeredUsers);
		$sended = 0;
		
		$returnString = "";
		
		foreach ($registeredUsers as $user)
		{
			$failures = array();
			
			if($isBeginEmail)
			{
				$failures = $sendMailer->sendBeginMail($user);
			}else 
			{
				$failures = $sendMailer->sendEndMail($user);
			}
				
			if(count($failures) > 0)
			{
				foreach ($failures as $failureEmail)
				{
					$returnString .= "- failed to -> ".$failureEmail."\n";
				}
			}else 
			{
				$sended++;					
			}
		}
		
		$returnString .= "Sended ".$sended."/".$total." emails.";
		
		return $returnString;
	}
	
	protected function getFormatedMessage($message)
	{
		$currentDate = date('d/m/Y H:i:s', time());
		
		return "[".$currentDate."]> ".$message;
	}
}