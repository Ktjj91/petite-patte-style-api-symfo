<?php
namespace App\Controller\Api;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/api/comments")]
class CategorieControllerApi extends AbstractController
{

    public function __construct(private CategorieRepository $categorieRepository)
    {
    }

    #[Route("/",methods: ["GET"])]
    public function getCategories() : JsonResponse
    {
        $categories = $this->categorieRepository->findAll();

       return $this->json($categories, context: ["groups" => ["comment.read"]]);

    }

    #[Route("/{id}",methods: ["GET"])]
    public function getCategoryById(int $id) : JsonResponse
    {
        $categorie = $this->categorieRepository->find($id);
        if(!$categorie){
            return $this->json(["Error" => "Categorie not found."],status: Response::HTTP_NOT_FOUND);
        }

        return $this->json($categorie,status: Response::HTTP_OK,context:["groups" => ["comment.read"]]);
    }


}
