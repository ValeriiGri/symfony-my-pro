<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /** @var PostRepository $postRepository */

    //    Нам понадобится класс PostRepository, отвечающий за работу с таблицей постов.
    // Однако в Symfony есть как минимум два способа работы с репозиторием.
    // Поскольку мы унаследовались от AbstractController,
    // мы можем достать репозиторий следующим образом и это будет первый способ:
    //
    //$repo = $this->getDoctrine()->getRepository(Post::class);
    //
    //То есть мы передаём в getRepository() в качестве переменной ссылку на сущность Post.
    // Теперь $repo является объектом класса PostRepository, ему доступны все методы,
    // предоставляемые стандартными репозиториями. Есть и другой способ, знакомый вам из курса по ООП,
    // - внедрение зависимостей через конструктор, что и сделано ниже

    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/posts", name="blog_posts")
     */

    //    Что нам нужно? Получить все записи и отправить их в шаблон.
    //    Создаём переменную $posts, в которой будем хранить результата выполнения запроса
    //    $this->postRepository->findAll(). Проще говоря, это массив записей,
    //    по которым мы пройдёмся в цикле в нашем шаблоне и достанем только названия статей.

    public function posts(){

        //получаем записи из репозитория и отправляем их в шаблон, который создался после создания контроллера
        $posts = $this->postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts]);
    }
}
