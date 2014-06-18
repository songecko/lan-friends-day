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
        ->add('isAvailable', 'checkbox', array(
        		'label'    => 'Habilitado?',
        		'required' => false
        ));
    }

    public function getName()
    {
        return 'lan_configuration';
    }
}
