<?php

namespace Odiseo\LanBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('dateBegin', 'datetime', array(
        		'label'    => 'Fecha Inicio',
        		'required' => false
        ))
        ->add('dateEnd', 'datetime', array(
        		'label'    => 'Fecha Fin',
        		'required' => false
        ))
        ->add('beginMailSended', 'checkbox', array(
        		'label'    => 'Inició envio de mail?',
        		'required' => false
        ))
        ->add('endMailSended', 'checkbox', array(
        		'label'    => 'Finalizó envio de mail?',
        		'required' => false
        ));
    }

    public function getName()
    {
        return 'lan_configuration';
    }
}
