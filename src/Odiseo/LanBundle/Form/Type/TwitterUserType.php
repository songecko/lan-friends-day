<?php

namespace Odiseo\LanBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TwitterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('user', 'entity', array(
        		'class'    => 'OdiseoLanBundle:User',
        		'label'    => 'Usuario'
        ))
        ->add('twitter', 'textarea', array(
        		'required' => true,
        		'label'    => 'Twitter'
        ));
    }

    public function getName()
    {
        return 'lan_twitteruser';
    }
}
