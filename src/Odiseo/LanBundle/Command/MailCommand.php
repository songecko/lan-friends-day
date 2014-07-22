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
		->addArgument('name', InputArgument::OPTIONAL, 'Nombre del destinatario')
		->addOption('yell', null, InputOption::VALUE_NONE, 'Letras en mayusculas')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = $input->getArgument('name');
		if ($name) {
			$text = 'Hola '.$name.' el comando se envio correctamente';
		} else {
			$text = 'Comando enviado correctamente';
		}

		if ($input->getOption('yell')) {
			$text = strtoupper($text);
		}

		$output->writeln($text);
	}
}