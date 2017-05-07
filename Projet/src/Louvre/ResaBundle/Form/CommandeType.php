<?php

namespace Louvre\ResaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Louvre\ResaBundle\Repository\BilletRepository;
use Symfony\Bridge\Doctrine\Form\Type\EnityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CommandeType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('typeBillet', ChoiceType::class, array(
            'label' => 'Type de Billet',
            'choices' => array(
               'Billet Journée' => 'Billet journée',
               'Billet demi journée' => 'Billet demi journée' ))
        )
        ->add('jourVisite', DateType::class, array(
            'label' => 'Jour de Visite',
            'widget' => 'single_text',
        ))
        ->add('billets', CollectionType::class, array(
        'label' => 'Formulaire',
        'entry_type'   => BilletType::class,
        'allow_add'    => true,
        'allow_delete' => true,

        ))
        ->add('email', TextType::class)
        ->add('save', SubmitType::class);
        
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\ResaBundle\Entity\Commande'
        ));
    }
}
