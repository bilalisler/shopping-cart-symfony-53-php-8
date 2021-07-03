<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Service\CartManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/create', name: 'admin.product.create')]
    public function create(Request $request, EntityManagerInterface $em, KernelInterface $kernel): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $formProductImage UploadedFile
             */
            $formProductImage = $form->get('product_image')->getData();

            if ($formProductImage instanceof UploadedFile) {
                $fileName = sprintf('%s_%s.%s', $product->getName(), uniqid(), $formProductImage->getClientOriginalExtension());
                $formProductImage->move($kernel->getProjectDir() . '/public/uploads/product_image/', $fileName);

                $product->setImage('/uploads/product_image/' . $fileName);
            }

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin.product.list');
        }

        return $this->render('admin/product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/list', name: 'admin.product.list')]
    public function list(EntityManagerInterface $em): Response
    {
        return $this->render('admin/product/list.html.twig', [
            'products' => $em->getRepository(Product::class)->findAll(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin.product.delete', methods: "DELETE")]
    public function delete($id, EntityManagerInterface $em): Response
    {
        try {
            /**
             * @var $product null|Product
             */
            $product = $em->getRepository(Product::class)->find($id);

            if (empty($product)) {
                throw new NotFoundHttpException('Ürün Bulunamadı!');
            }

            $em->remove($product);
            $em->flush();

            return new JsonResponse([
                'message' => 'Başarılı'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => 'Bir hata oluştu'
            ], 400);
        }
    }
}
