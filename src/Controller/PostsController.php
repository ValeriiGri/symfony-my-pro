<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
//use http\Env\Request;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/posts/new", name="new_blog_post")
     * @param Request $request
     * @param Slugify $slugify
     *
     */


    public function addPost (Request $request, Slugify $slugify){
        dump('111111');
        # Сначала мы создаём объект нашей сущности, которую и будем сохранять
        $post = new Post();

        # Дальше мы создаём форму с помощью метода createForm,
        # который обязательным параметром принимает наш класс PostType и объект класса Post
        $form = $this->createForm(PostType::class, $post);

        # Далее мы обрабатываем наш $request с помощью метода handleRequest,
        # который стал нам доступен после создания инстанса формы
        $form->handleRequest($request);

        # Теперь мы проверяем, нажата ли кнопка под формой и является ли она валидной
        # в соответствии с теми правилами валидации, которые мы применили к нашей сущности.
        # Если оба этих условия выполняются, мы устанавливаем slug с помощью уже известного нам метода slugify,
        # куда передаём title статьи. Время для поля created_at берём текущее,
        # которое возвращает нам объект класса DateTime() по умолчанию
        if($form->isSubmitted() && $form->isValid()){
            $post->setSlug($slugify->slugify($post->getTitle()));
            $post->setCreatedAt(new \DateTime());

            # Вызываем наш Manager, подготавливаем (persist) и сохраняем пост (flush).
            # После сохранения редиректим пользователя на страницу со всеми постами
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('blog_posts');
        }

        # А сам метод addPosts() возвращает шаблон, куда в качестве аргумента передаём $form->createView().
        # Этот метод создаст саму форму в нашей вёрстке

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()]);
    }

    /**
     * @Route("/posts/{slug}/edit", name="blog_post_edit")
     */

    public function edit(Post $post, Request $request, Slugify $slugify){
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $post->setSlug($slugify->slugify($post->getTitle()));
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('blog_show',[
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('posts/new.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="blog_show")
     */

    /*В первую очередь обратите внимание на роутинг. Я уже упоминал в одном из первых уроков,
    что значения, заключенные в фигурные скобки, называются плейсхолдерами -
    они меняются в зависимости от запроса пользователя. А ещё, Symfony достаточно умный, чтобы понимать,
    по какому критерию вы достаёте данные, так что если вы напишите вместо slug id, title или body,
    он отдаст вам нужные данные конкретного поста! Всё это происходит благодаря аннотации ParamConverter,
    которую в нашем случае использовать необязательно*/

    //этот метод дб последним, чтобы не вызывался вместо того у которого Route /posts/...
    public function showPost (Post $post){
        return $this->render('posts/show.html.twig',[

            'post'=> $post]);
    }
}
