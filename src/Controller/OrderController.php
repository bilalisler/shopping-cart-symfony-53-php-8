<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Form\CartType;
use App\Form\OrderCompleteType;
use App\Service\CartManager;
use App\Service\OrderManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/order")]
class OrderController extends AbstractController
{
    #[Route("/list", name: 'order.list')]
    public function orderList(Request $request)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $orderList = $em->getRepository(Order::class)->findBy([
            'user' => $this->getUser()
        ]);

        return $this->render('order/order_list.html.twig', [
            'orderList' => $orderList
        ]);
    }

    #[Route('/complete', name: 'order.complete')]
    public function orderComplete(CartManager $cartManager, OrderManager $orderManager, Request $request): Response
    {
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setStatus(Order::ORDER_STATUS_COMPLETE);
        $order->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(OrderCompleteType::class, $order);
        $form->handleRequest($request);

        $cart = $cartManager->getCurrentCart();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($cart->getItems() as $cartItem) {
                $orderItem = new OrderItem();
                $orderItem->setOrderRef($orderItem);
                $orderItem->setQuantity($cartItem->getQuantity());
                $orderItem->setProduct($cartItem->getProduct());

                $order->addItem($orderItem);
            }
            $cartManager->removeCart($cart);
            $orderManager->save($order);

            $this->redirectToRoute('order.complete.success',['id' => $order->getId()]);
        }

        return $this->render('order/order_complete.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

    #[Route("/{id}/complete-success", name: 'order.complete.success')]
    public function orderCompleteSuccess(Order $order)
    {
        return $this->render('order/order_complete_success.html.twig', [
            'order' => $order
        ]);
    }
}
