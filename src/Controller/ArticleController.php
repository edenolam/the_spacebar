<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
//    /**
//     * Currently unused: just showing a controller with a constructor!
//     */
//    private $isDebug;
//    public function __construct(bool $isDebug)
//    {
//        $this->isDebug = $isDebug;
//    }
    /**
     * @param ArticleRepository $repository
     * @return Response
     * @Route ("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();
        return $this->render('article/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param $slug
     * @param EntityManagerInterface $em
     * @return Response
     * @Route ("/news/{slug}", name="article_show"))
     */
    public function show($slug, EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $slug]);
        if (!$article){
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);

    }

    /**
     * @Route ("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"}))
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger): JsonResponse
    {
        $logger->info('post');
        return $this->json(['hearts' => rand(5, 100), 'post' => rand(5, 654654)]);
    }

}