<?php

namespace IATBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityRepository;

class DealType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userId', 'entity', array(
                    'class' => 'IATBundle:User',
            		'query_builder' => function (EntityRepository $er) {
            			return $er->createQueryBuilder('u')
            				->Where("u.type = :type")
            				->setParameter(':type', 'merchant')
            				->groupBy("u.id")
            				->orderBy('u.id');
            		},
            		//'data' => $this->dealObject,
            		'property' => 'fullName',
            		//'disabled' => true,
            		'empty_data'  => 'Merchant Name',
            		'constraints' => new Assert\NotBlank(array('message' => "Please select merchant."))
            	)
            )
            //->add('startDate')
            //->add('endDate')
            //->add('price')
            //->add('departureAirport')
            ->add('destination', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter destination")),
            	)
            )
            ->add('dealTitle', 'text',
            		array(
            				'constraints' => new Assert\NotBlank(array('message' => "Please Enter title of the deal")),
            	)
            )
            //->add('hotelName')
            //->add('noOfNights')
            //->add('boardBasis')
            //->add('departureMonths')
            //->add('createdAt')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IATBundle\Entity\Deal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'iatbundle_deal';
    }
}
