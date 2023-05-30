<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordRequestFormType extends AbstractType
{
    /**
     * Construit le formulaire de demande de réinitialisation du mot de passe.
     *
     * @param FormBuilderInterface $builder L'instance du constructeur de formulaire.
     * @param array $options Les options du formulaire.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Entrez votre mail',
                'attr' => [
                    'placeholder' => 'example@email.com',
                    'class' => 'form-control'
                ]
            ])
        ;
    }


    /**
     * Configure les options du formulaire.
     *
     * @param OptionsResolver $resolver L'instance du résolveur d'options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
