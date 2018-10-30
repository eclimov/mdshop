<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Validator\Constraints\Image;
use \App\Entity\BankAffiliate;
use Symfony\Component\Validator\Constraints\NotNull;

class CompanyType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255,]),
                ],
                'attr' => [
                    'autofocus' => true,
                    'autocomplete' => 'off',
                ],
            ])
            ->add('shortName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 25,]),
                ],
                'attr' => [
                    'maxlength' => '25',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('bankAffiliate', EntityType::class, [
                'class' => BankAffiliate::class,
                'choice_label' => function(BankAffiliate $bankAffiliate) {
                    return '[' . $bankAffiliate->getBank()->getName() . ']' . $bankAffiliate->getAffiliateNumber();
                },
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('iban', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255,]),
                ],
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('fiscalCode', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 13,]),
                ],
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('vat', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 7,]),
                ],
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('hidden', CheckboxType::class, [
                'label' => 'Hide from simple users',
                'required' => false,
                //'help' => 'Some users will not see it',
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
