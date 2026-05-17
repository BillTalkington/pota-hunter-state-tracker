<?php

namespace App\Form;

use App\Entity\Qso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QsoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('activatorCallsign')
            ->add('parkReference')
            ->add('state')
            ->add('band')
            ->add('mode')
            ->add('contactedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Qso::class,
        ]);
    }
}
