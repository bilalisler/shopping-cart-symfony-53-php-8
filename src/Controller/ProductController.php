<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Service\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product.detail')]
    public function index(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $cartItem CartItem
             */
            $cartItem = $form->getData();
            $cartItem->setProduct($product);

            $cart = $cartManager->getCurrentCart();

            $cart
                ->addItem($cartItem)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
