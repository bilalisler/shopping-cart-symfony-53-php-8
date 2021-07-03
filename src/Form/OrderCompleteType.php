<?php

namespace App\Form;


use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderCompleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('address', TextareaType::class, [
                'label' => 'common.order_address',
                'constraints' => [
                    new NotBlank(['message' => 'Bu alan gereklidir'])
                ]
            ])
            ->add('paymentType', ChoiceType::class, [
                'expanded' => true,
                'label' => 'common.payment_type',
                'choices' => array_flip([
                    Order::PAYMENT_TYPE_PAY_AT_DOOR => 'Kapıda Ödeme',
                    Order::PAYMENT_TYPE_CREDIT_CART => 'Kredi Kartı'
                ]),
                'constraints' => [
                    new NotBlank(['message' => 'Bu alan gereklidir'])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'button.complete_order'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}