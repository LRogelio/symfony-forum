<?php

namespace AppBundle\Form\Panel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangePasswordType extends AbstractType
{
	private $encoder;
	private $tokenStorage;

	public function __construct(UserPasswordEncoderInterface $encoder, TokenStorage $tokenStorage)
	{
		$this->encoder = $encoder;
		$this->tokenStorage = $tokenStorage;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('currentPassword',  PasswordType::class, [
				'label' => 'Current Password',
				'required' => false,
				'constraints' => [
					new NotBlank()
				]
			])
			->add('newPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'required' => false,
				'invalid_message' => 'The passwords must match',
				'first_options' => ['label' => 'New password'],
				'second_options' => ['label' => 'Confirm new password'],
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
		$resolver->setDefaults([
			'constraints' => [
				new Callback([$this, 'validateCurrentPassword'])
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_panel_change_password_form';
	}

	public function validateCurrentPassword($data, ExecutionContextInterface $context)
	{
		$user = $this->tokenStorage->getToken()->getUser();

		if ($data['currentPassword'] && !$this->encoder->isPasswordValid($user, $data['currentPassword'])) {
			$context->buildViolation('Invalid current password')
		        ->atPath('[currentPassword]')
		        ->addViolation();
		}
	}
}