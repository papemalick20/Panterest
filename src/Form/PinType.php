<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('imageFile', VichImageType::class, [
            'label' => 'Imgage(JPG or PNG file)',
            'required' => false,
            'download_label' => '...',
            'allow_delete' => true,
            'download_uri' => true,
            'imagine_pattern'=> 'squared_thumbnail_small'
            
            ])
            ->add('title')
            ->add('description')
            ;
           
    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
