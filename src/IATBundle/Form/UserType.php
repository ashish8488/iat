<?php

namespace IATBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => 'Please Enter First Name')),
            	)
            )
            ->add('lastName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => 'Please Enter First Name')),
            	)
            )
            ->add('email', 'text',
            	array(
            		'constraints' => array(
            			new Assert\NotBlank(array('message' => 'Please Enter Email Address')),
            			new Assert\Regex(array('pattern' => "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",'message' => "Please enter valid email id."))
            		)
            	)
            )
            ->add('password', 'repeated',
            		array(
            				'type' => 'password',
            				'constraints' => array(
            					new Assert\NotBlank(array('message' => 'Please enter password')),
            				),
            				'invalid_message' => 'Passwords does not match.'
            		)
            );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IATBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'iatbundle_user';
    }
}
