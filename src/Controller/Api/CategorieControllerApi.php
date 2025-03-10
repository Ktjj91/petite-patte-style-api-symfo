<?php
namespace App\Controller\Api;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/api/comments")]
class CategorieControllerApi extends AbstractController
{

    public function __construct(private CategorieRepository $categorieRepository)
    {
    }

    public function getCategories(): JsonResponse
    {
        $categories = $this->categorieRepository->findAll();

        $this->json($categories,context: []);

    }


}
