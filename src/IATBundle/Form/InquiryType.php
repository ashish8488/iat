<?php

namespace IATBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityRepository;

class InquiryType extends AbstractType
{
	
	public function __construct($preSetData = array(), $dealObject)
	{
		$this->preSetData = $preSetData;
		$this->dealObject = $dealObject;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dealId', 'entity', array(
                    'class' => 'IATBundle:Deal',
            		'query_builder' => function (EntityRepository $er) {
            			return $er->createQueryBuilder('d')
            				->Where("d.dealLink = :dealLink")
            				->setParameter(':dealLink', $this->preSetData['dealLink'])
            				->groupBy("d.dealLink")
            				->orderBy('d.id');
            		},
            		'data' => $this->dealObject,
            		'property' => 'id',
            		//'disabled' => true,
            		'constraints' => new Assert\NotBlank(array('message' => "Please select deal"))
            	)
            )
            ->add('departureDate', 'datetime', array(
            		'widget' => 'single_text',
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter Preffered departure date")),
            	)
            )
            ->add('cust1FName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter First Customer's First Name")),
            	)
            )
            ->add('cust1LName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter First Customer's Last Name")),
            	)
            )
            ->add('cust1dob', 'datetime',
            	array(
            		'widget' => 'single_text',
            		/* 'format' => 'MM/dd/yyyy', */
            		'invalid_message' => "Please enter correct date.",
            		'constraints' => array(
            			new Assert\NotBlank(array('message' => "Please Enter First Customer's Date Of Birth")),
            		)
            	)
            )
            ->add('cust2FName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter Second Customer's First Name")),
            	)
            )
            ->add('cust2LName', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter Second Customer's Last Name")),
            	)
            )
            ->add('cust2dob', 'datetime',
            	array(
            		'widget' => 'single_text',
            		/* 'format' => 'MM/dd/yyyy', */
            		'invalid_message' => "Please enter correct date.",
            		'constraints' => array(
            			new Assert\NotBlank(array('message' => "Please Enter Second Customer's Date Of Birth")),
            		)
            	)
            )
            ->add('phone', 'text',
            		array(
            			'invalid_message' => "Please enter only numbers.",
            			'constraints' => array(
            				new Assert\NotBlank(array('message' => "Please Enter Phone Number")),
            				new Assert\Regex(array('pattern' => "/^[0-9+].*$/",'message' => "please enter valid phone number"))
            				)
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
            ->add('prefferedCallTime', 'choice', array(
            		'label' => "Preffered Call Time",
            		'choices'  => array(
            			'09:00-10:00' => '09:00-10:00',
            			'10:00-11:00' => '10:00-11:00',
            			'11:00-12:00' => '11:00-12:00',
            			'12:00-13:00' => '12:00-13:00',
            			'13:00-14:00' => '13:00-14:00',
            			'14:00-15:00' => '14:00-15:00',
            			'15:00-16:00' => '15:00-16:00',
            			'16:00-17:00' => '16:00-17:00',
            			'17:00-18:00' => '17:00-18:00',
            			'18:00-19:00' => '18:00-19:00',
            			'19:00-20:00' => '19:00-20:00',
            			'20:00-20:30' => '20:00-20:30'
            		),
            		'choices_as_values' => true,
            		'placeholder' => 'Please select preffered time to call',
            		'empty_data'  => 'Please select preffered time to call',
            		'constraints' => new Assert\NotBlank(array('message' => "Please selectpreffered call time")),
            	)
            		
            )
            ->add('address1', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter address line one")),
            	)
            )
            ->add('address2', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter address line two")),
            	)
            )
            ->add('city', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter city")),
            	)
            )
            ->add('state', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter state")),
            	)
            )
            ->add('zipcode', 'text',
            		array(
            			'invalid_message' => "Please enter only numbers.",
            			'constraints' => array(
            				new Assert\NotBlank(array('message' => "Please Enter zipcode")),
            				//new Assert\Regex(array('pattern' => "/^[0-9+].*$/",'message' => "please enter valid zipcode"))
            			)
            		)
            )
            ->add('country', 'choice', array(
            		'label' => "Country",
            		'choices'  => array(
            			'United Kingdom' => 'United Kingdom',
            			'United States' => 'United States',
            			'Australia' => 'Australia',
            			'Canada' => 'Canada',
            			'France' => 'France',
            			'New Zealand' => 'New Zealand',
            			'India' => 'India',
            			'Brazil' => 'Brazil'
            		),
            		'choices_as_values' => true,
            		//'placeholder' => 'Please select country',
            		//'empty_data'  => 'Please select country',
            		//'preferred_choices' => array('United Kingdom'),
            		'constraints' => new Assert\NotBlank(array('message' => "Please select country")),
            	)
            )
            ->add('cust1GroupOnCode', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter First Customer's Group On Code")),
            	)
            )
            ->add('cust2GroupOnCode', 'text',
            	array(
            		'constraints' => new Assert\NotBlank(array('message' => "Please Enter Second Customer's Group On Code")),
            	)
            )
            ->add('cust1GroupOnCodeFile', 'file',
            	array(
            		'constraints' => array(
            			//new Assert\NotBlank(array('message' => "Please upload First Customer's Group On Code PDF File")),
            			new Assert\File(array('mimeTypes' => array("application/pdf"), 'mimeTypesMessage' => "The file type is not allowed. Please upload only PDF files.", 'maxSize' => "5M", 'maxSizeMessage' => "Please upload maximum 5 MB files." ))
            		)
            	)
            )
            ->add('cust2GroupOnCodeFile', 'file',
            	array(
            		'constraints' => array(
            			//new Assert\NotBlank(array('message' => "Please upload Second Customer's Group On Code PDF File")),
            			new Assert\File(array('mimeTypes' => array("application/pdf"), 'mimeTypesMessage' => "The file type is not allowed. Please upload only PDF files.", 'maxSize' => "5M", 'maxSizeMessage' => "Please upload maximum 5 MB files." ))
            		)
            	)
            )
            ->add('terms', 'checkbox', array(
            	'mapped' => false,
            	'constraints' => new Assert\IsTrue(array('message' => "Please accept terms and conditions")),
            )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IATBundle\Entity\Inquiry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'iatbundle_inquiry';
    }
}
