<?php

namespace Odiseo\LanBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', 'text', array(
        		'required' => true,
        		'label'    => 'Nombre Usuario'
        ))
        ->add('email', 'text', array(
        		'required' => true,
        		'label'    => 'Email'
        ))
        ->add('plainPassword', 'password', array(
        		'required' => true,
        		'label'    => 'Password'
        ))
        ->add('firstName', 'text', array(
        		'required' => true,
        		'label'    => 'Nombre'
        ))
        ->add('lastName', 'text', array(
        		'required' => true,
        		'label'    => 'Apellido'
        ));
    }

    public function getName()
    {
        return 'lan_user';
    }
}
