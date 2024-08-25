<?php

namespace App\Controller;

use App\School\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    private SchoolRepository $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    #[Route('/docs/test', name: 'app_test')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $schools = $this->schoolRepository->findAll();

        $entityManager->remove($schools[0]);
        $entityManager->flush();

        dd($schools);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
