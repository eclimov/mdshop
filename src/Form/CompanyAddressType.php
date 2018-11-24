<?php

namespace App\Form;

use App\Entity\CompanyAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use \Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyAddressType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=>'1', 'max' => 255,]),
                ],
                'attr' => [
                    'autofocus' => true,
                    'autocomplete' => 'off',
                ],
            ])
            ->add('juridic', CheckboxType::class, [
                'label' => 'Juridic',
                'required' => false,
                //'help' => 'Mark address as juridic',
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
            'data_class' => CompanyAddress::class,
        ]);
    }
}
