<?php

namespace App\Form;

use App\Entity\FicheForfait;
use App\Entity\FicheForfaitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FicheFType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idType', EntityType::class, [
                'class' => FicheForfaitType::class,
                'choice_label' => 'libelle'
            ])
            ->add('qte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheForfait::class,
        ]);
    }
}
