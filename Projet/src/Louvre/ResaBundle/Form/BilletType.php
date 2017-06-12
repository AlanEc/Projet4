<?php

namespace Louvre\ResaBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
           $builder
            ->add('nom',    TextType::class)
            ->add('prenom',    TextType::class)
            ->add('dateNaissance',      DateType::class, [
                'years' => range(1900, 2017, 1)
            ])
            ->add('tarifReduit', CheckboxType::class, array(
                'required' => false,
                'label' => 'Tarif réduit ( Sur présentation d\'un document justificatif pouvant votre status: étudiant, employé du musée, d’un service du Ministère de la Culture, militaire )'))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\ResaBundle\Entity\Billet'
        ));
    }
}
