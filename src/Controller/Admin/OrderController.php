<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Service\CartManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/list', name: 'admin.order.list')]
    public function list(EntityManagerInterface $em): Response
    {
        return $this->render('admin/order/list.html.twig', [
            'orders' => $em->getRepository(Order::class)->findAll(),
        ]);
    }

    #[Route('/{id}/approve', name: 'admin.order.approve', methods: "PUT")]
    public function approve($id, EntityManagerInterface $em): Response
    {
        try {
            /**
             * @var $order null|Order
             */
            $order = $em->getRepository(Order::class)->find($id);

            if (empty($order)) {
                throw new NotFoundHttpException('Sipariş Bulunamadı!');
            }

            $order->setStatus(Order::ORDER_STATUS_APPROVED);
            $order->setApprovedAt(new \DateTime('now'));

            $em->persist($order);
            $em->flush();

            return new JsonResponse([
                'message' => 'Başarılı'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Bir hata oluştu'
            ], $e->getCode());
        }
    }
}
