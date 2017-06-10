<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TopicCommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('topic', EntityType::class, [
		        'label' => 'Topic',
		        'required' => false,
		        'placeholder' => '- Select topic -',
		        'class' => 'AppBundle:Topic',
		        'choice_label' => 'title',
		        'group_by' => function($value, $key, $index) {
			        return $value->getCategory()->getName();
		        },
		        'constraints' => [
			        new NotBlank()
		        ]
	        ])
            ->add('message', TextareaType::class, [
            	'label' => 'Message',
	            'required' => false,
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
            'data_class' => 'AppBundle\Entity\TopicComment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_topic_comment';
    }
}
