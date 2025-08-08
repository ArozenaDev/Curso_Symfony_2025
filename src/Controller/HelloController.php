<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [  
        ['message' => 'Hello', 'created' => '2025/06/12'],
        ['message' => 'Hi', 'created' => '2025/04/12'],
        ['message' => 'Bye!', 'created' => '2024/05/12']
    ];

    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreated(new DateTime());

        $repository = $entityManager->getRepository(MicroPost::class);
        $post = $repository->find(10);
        $comment = $post->getComments()[0];

        $post->removeComment($comment);
        $entityManager->persist($post);
        $entityManager->flush();

        //dd($post);

        // $comment = new Comment();
        // $comment->setText('Hello');
        // $comment->setPost($post);
        // //$post->addComment($comment);
        // $entityManager->persist($comment, true);
        // $entityManager->flush();


        // $user = new User();
        // $user->setEmail('email@email.com');
        // $user->setPassword('12345678');


        // $profile = new UserProfile();
        // $profile->setUser($user);
        // $entityManager->persist($profile, true);
        // $entityManager->flush();

        // $repository = $entityManager->getRepository(UserProfile::class);
        // $profile = $repository->find(1);
        // $entityManager->remove($profile, true);
        // $entityManager->flush();


        return $this->render('hello/index.html.twig', 
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render('hello/show_one.html.twig', 
            ['message' => $this->messages[$id]]);
        //return new Response($this->messages[$id]);
    }
}