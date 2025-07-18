<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Form\MicroPostTypeForm;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {      
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response 
    {
        $form = $this->createForm(MicroPostTypeForm::class, new MicroPost());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            //Add a flash
            $this->addFlash('success', 'Your micro post has been added');

            return $this->redirectToRoute('app_micro_post');
            //Redirect

        }

        return $this->render(
            'micro_post/add.html.twig',
            [
                'form' => $form
            ]
        );

    }

     #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response 
    {

        $form = $this->createForm(MicroPostTypeForm::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();

            //Add a flash
            $this->addFlash('success', 'Your micro post has been updated');

            return $this->redirectToRoute('app_micro_post');
            //Redirect

        }

        return $this->render(
            'micro_post/edit.html.twig',
            [
                'form' => $form
            ]
        );

    }
}
