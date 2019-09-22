<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\CompanyAddress;
use App\Entity\CompanyEmployee;
use App\Entity\Invoice;
use App\Repository\CompanyEmployeeRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvoiceType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $companyInitiator = $options['companyInitiator'];
        $company = $options['company'];
        $builder
            ->add('orderDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('deliveryDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('carrier', EntityType::class, [
                'class' => Company::class,
                'choice_label' => function(Company $company) {
                    return $company->getName();
                },
                'preferred_choices' => [$company,],
            ])
            ->add('attachedDocument', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=>'1', 'max' => 255,]),
                ],
                'attr' => [
                    'placeholder' => '-',
                    'autocomplete' => 'off',
                ],
                'empty_data' => '-',
            ])
            ->add('loadingPoint', EntityType::class, [
                'class' => CompanyAddress::class,
                'choice_label' => 'address',
                'query_builder' => function (EntityRepository $er) use ($companyInitiator) {
                    return $er->createQueryBuilder('a')
                        ->where('a.company = :company')
                        ->setParameters([
                            'company' => $companyInitiator,
                        ])
                        ->orderBy('a.juridic', 'ASC');
                },
            ])
            ->add('unloadingPoint', EntityType::class, [
                'class' => CompanyAddress::class,
                'choice_label' => 'address',
                'query_builder' => function (EntityRepository $er) use ($company) {
                    return $er->createQueryBuilder('a')
                        ->where('a.company = :company')
                        ->setParameters([
                            'company' => $company,
                        ]);
                },
            ])
            ->add('approvedByEmployee', EntityType::class, [
                'class' => CompanyEmployee::class,
                'choice_label' => function (CompanyEmployee $companyEmployee) {
                    return '[' . $companyEmployee->getPosition() . '] ' . $companyEmployee->getName();
                },
                'query_builder' => function (CompanyEmployeeRepository $companyEmployeeRepository) use ($companyInitiator) {
                    return $companyEmployeeRepository->getCompanyEmployeesOrderDirectorLastQb($companyInitiator);
                },
            ])
            ->add('processedByEmployee', EntityType::class, [
                'class' => CompanyEmployee::class,
                'choice_label' => function (CompanyEmployee $companyEmployee) {
                    return '[' . $companyEmployee->getPosition() . '] ' . $companyEmployee->getName();
                },
                'query_builder' => function (CompanyEmployeeRepository $companyEmployeeRepository) use ($companyInitiator) {
                    return $companyEmployeeRepository->getCompanyEmployeesOrderDirectorLastQb($companyInitiator);
                },
            ])
            ->add('recipientName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=>'1', 'max' => 255,]),
                ],
                'attr' => [
                    'placeholder' => '-',
                    'autocomplete' => 'off',
                ],
                'empty_data' => '-',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'companyInitiator' => null,
            'company' => null,
        ]);
    }
}
