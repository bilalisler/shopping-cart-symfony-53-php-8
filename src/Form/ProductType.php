<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\Product;
use App\Form\EventListener\ClearCartListener;
use App\Form\EventListener\RemoveCartItemListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => 'common.name'
            ])
            ->add('description', TextType::class, [
                'label' => 'common.description'
            ])
            ->add('price', TextType::class, [
                'label' => 'common.price'
            ])
            ->add('product_image', FileType::class, [
                'mapped' => false,
                'label' => 'common.product_image'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'button.save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}