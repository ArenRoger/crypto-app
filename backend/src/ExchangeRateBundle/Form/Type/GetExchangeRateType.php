<?php

namespace App\ExchangeRateBundle\Form\Type;

use App\ExchangeRateBundle\DTO\ExchangeRateGetDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class GetExchangeRateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => 2,
                        'max' => 128,
                    ]),
                ],
            ])
            ->add('to', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'min' => 2,
                        'max' => 128,
                    ]),
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => ExchangeRateGetDto::class,
        ]);
    }
}
