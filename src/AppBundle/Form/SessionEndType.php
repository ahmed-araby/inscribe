<?php

namespace AppBundle\Form;

use AppBundle\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SessionEndType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('endedAt',DateTimeType::class)
		    ->add('save', SubmitType::class)
	    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
	    $resolver->setDefaults(array(
		    'data_class' => Session::class
	    ));

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_session_end';
    }
}