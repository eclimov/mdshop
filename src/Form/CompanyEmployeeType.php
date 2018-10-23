<?php

namespace App\Form;

use App\Entity\CompanyEmployee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use \Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyEmployeeType extends AbstractType
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
                    new Length(['min'=>'1', 'max' => 255,]),
                ],
                'attr' => [
                    'autofocus' => true,
                    'autocomplete' => 'off',
                ],
            ])
            ->add('position', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255,]),
                ],
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
            'data_class' => CompanyEmployee::class,
        ]);
    }
}
