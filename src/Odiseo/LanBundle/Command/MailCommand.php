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
		//Check if the campaign is active
		$text = "Test";
		$output->writeln($text);
	}
}