<?php

namespace BulmaCSSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DemoType extends AbstractType
{
	private $selectOptions = [
		'Category 1' => 1,
		'Category 2' => 2,
		'Category 3' => 3,
		'Category 4' => 4
	];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
	        ->add('title', TextType::class, [
	        	'label' => 'Title',
		        'required' => false,
		        'mapped' => false,
		        'attr' => ['class' => 'myclass'],
		        'constraints' =>[
		        	new NotBlank()
		        ]
	        ])
	        ->add('category', ChoiceType::class, [
	        	'placeholder' => '- select category -',
		        'label' => 'Category',
		        'required' => false,
		        'choices' => $this->selectOptions,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('description', TextareaType::class, [
	        	'label' => 'Description',
		        'required' => false,
		        'mapped' => false,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('interests', ChoiceType::class, [
	        	'label' => 'Interests',
		        'required' => false,
		        'mapped' => false,
		        'expanded' => true,
		        'multiple' => true,
		        'choices' => $this->selectOptions,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('interests2', ChoiceType::class, [
		        'label' => 'Interests2',
		        'required' => false,
		        'mapped' => false,
		        'expanded' => true,
		        'multiple' => false,
		        'choices' => $this->selectOptions,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('interests3', ChoiceType::class, [
		        'label' => 'Interests3',
		        'required' => false,
		        'mapped' => false,
		        'expanded' => false,
		        'multiple' => true,
		        'choices' => $this->selectOptions,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('terms', CheckboxType::class, [
		        'label' => 'I accept terms of the site',
		        'required' => false,
		        'mapped' => false,
		        'constraints' =>[
			        new NotBlank()
		        ]
	        ])
	        ->add('cancel', ButtonType::class, [
	        	'label' => 'Cancel'
	        ])
	        ->add('save', SubmitType::class, [
	        	'label' => 'Save',
		        'attr' => [
		        	'class' => 'is-primary'
		        ]
	        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bulmacssbundle_demo';
    }
}
