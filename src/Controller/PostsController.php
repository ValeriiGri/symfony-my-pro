<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/posts", name="posts")
     */
    public function index()

    {
        $posts = [
            'post_1' => [
                'title' => 'Заголовок первого поста',
                'body' => 'Тело первого поста'
            ],
            'post_2' => [
                'title' => 'Заголовок второго поста',
                'body' => 'Тело второго поста'
            ]
        ];
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
//        return $this->render('posts/index.html.twig', [
//            'controller_name' => 'PostsController',
//        ]);
    }
}
