<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
	        ->add('category', EntityType::class, [
	        	'label' => 'Category',
		        'required' => false,
		        'placeholder' => '- Select Category -',
		        'class' => 'AppBundle\Entity\TopicCategory',
		        'choice_label' => 'name',
		        'query_builder' => function(EntityRepository $er) {
	        	    return $er->createQueryBuilder('c')
			            ->orderBy('c.name', 'ASC');
		        },
		        'empty_data' => 3,
		        'constraints' => [
			        new NotBlank()
		        ]
	        ])
	        ->add('title', TextType::class, [
	        	'label' => 'Title',
		        'required' => false,
		        'constraints' => [
		        	new NotBlank()
		        ]
	        ]);

        if ($options['comments']) {
        	$builder->add('comments', CollectionType::class, [
		        'entry_type' => TopicCommentType::class,
		        'label' => false,
		        'entry_options' => [],
		        'by_reference' => true
	        ]);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Topic',
	        'comments' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_topic_form';
    }
}
