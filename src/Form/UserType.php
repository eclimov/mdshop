<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use function count;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var UserPasswordEncoderInterface $passwordEncoder */
        $passwordEncoder = $options['password_encoder'];

        $builder
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '-'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                $constraints = [];
                $passwordAttributes = [];
                if (!$user || null === $user->getId()) {  // If creating new user, non-empty password is obligatory
                    $constraints[] = new NotBlank();
                } else {
                    $passwordAttributes['placeholder'] = 'leave this field empty to not update it';
                }

                $form->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => [
                        'label' => 'Password',
                        'attr' => $passwordAttributes,
                    ],
                    'second_options' => [
                        'label' => 'Repeat Password',
                        'attr' => $passwordAttributes,
                    ],
                    'constraints' => $constraints,
                ));
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, static function (FormEvent $event) use ($passwordEncoder) {
                $eventData = $event->getData();
                if($eventData && empty($eventData['password']['first'])) {  // If updating existing user, password is not required
                    unset($eventData['password']);
                } else if(count(array_unique(array_values($eventData['password']))) === 1) {  // Check if 'password' and 'repeat password' are equal
                    /** @var User $user */
                    $user = $event->getForm()->getData();
                    $password = $eventData['password']['first'];
                    $passwordEncoded = $passwordEncoder->encodePassword($user, $password);
                    $eventData['password']['first'] = $passwordEncoded;
                    $eventData['password']['second'] = $passwordEncoded;
                }
                $event->setData($eventData);
            })
            ->add('role', ChoiceType::class, [
                'choices' => User::ROLES,
                'choice_label' => static function ($choiceValue, $key, $value) {
                    return strtoupper($value);
                },
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => User::class,
            ))
            ->setRequired('password_encoder');
    }
}
