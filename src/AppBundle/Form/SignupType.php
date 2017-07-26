<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignupType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, [
				'label' => 'Username',
				'required' => false,
				'constraints' => [
					new NotBlank()
				]
			])
			->add('email', TextType::class, [
				'label' => 'Email',
				'required' => false,
				'constraints' => [
					new NotBlank(),
					new Email()
				]
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'required' => false,
				'invalid_message' => 'The passwords must match',
				'first_options' => ['label' => 'Password'],
				'second_options' => ['label' => 'Confirm Password'],
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
			'data_class' => 'AppBundle\Entity\User',
			'constraints' => [
				new UniqueEntity(['fields' => 'username', 'message' => 'This username is not available']),
				new UniqueEntity(['fields' => 'email', 'message' => 'An user with this email already exists'])
			]
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_signup_form';
	}
}