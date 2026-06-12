<?php

namespace App\Form;

use App\Enum\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => "Ваше ім'я або нікнейм"],
                'constraints' => [new Length(max: 100)],
            ])
            ->add('bio', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 3, 'placeholder' => 'Розкажіть про себе...'],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => '+380 00 000 00 00'],
                'constraints' => [new Length(max: 30)],
            ])
            ->add('location', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Місто, регіон'],
                'constraints' => [new Length(max: 120)],
            ])
            ->add('avatarFile', FileType::class, [
                'label' => 'Фото профілю',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Підтримуються JPG, PNG, WebP',
                    ),
                ],
            ])
            ->add('theme', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Тема оформлення',
                'choices' => array_combine(
                    array_map(fn(Theme $t) => $t->label(), Theme::cases()),
                    array_map(fn(Theme $t) => $t->value, Theme::cases()),
                ),
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\Profile::class,
        ]);
    }
}
