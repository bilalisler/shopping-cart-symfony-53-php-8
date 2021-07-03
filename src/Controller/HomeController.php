<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/change-language/{locale}', name: 'change.language')]
    public function changeLanguage($locale,Request $request): Response
    {
        $request->getSession()->set('_locale',$locale);

        return $this->redirect($request->server->get('HTTP_REFERER'));
    }

    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}