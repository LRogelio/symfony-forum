<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TopicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('title', TextType::class, [
	        	'label' => 'Title',
		        'required' => false,
		        'constraints' => [
		        	new NotBlank()
		        ]
	        ])
	        ->add('category', EntityType::class, [
		        'label' => 'Category',
		        'required' => false,
		        'placeholder' => '- Select category -',
		        'class' => 'AppBundle:TopicCategory',
		        'choice_label' => 'name',
		        'constraints' => [
			        new NotBlank()
		        ]
	        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Topic'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_topic';
    }
}
