<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;

/**
 * show all subjetc in the forum section
 * @Route("/post", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * show all subjetc in the forum section
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {

        $post = new post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $post->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('post/admin/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('post_index');
        }
        return $this->render('post//admin/edit.html.twig', ["form" => $form->createView(), 'post' => $post]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Post $post): response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('post_index');
    }

    /**
     * Like system for comment
     *
     * @Route("/flexUp/{id}", name="flex")
     */
    public function flexUp(Post $post): response
    {
        /** @phpstan-ignore-next-line */
        if ($this->getUser()->isInFlexlist($post)) {
            $post->setFlexPoint($post->getFlexPoint() - 1);
            /** @phpstan-ignore-next-line */
            $post->getAuthor()->setTotalFlex($post->getAuthor()->getTotalFlex() - 1);
            /** @phpstan-ignore-next-line */
            $this->getUser()->removeFlexlist($post);
        } else {
            /** @phpstan-ignore-next-line */
            $this->getUser()->addFlexlist($post);
            $post->setFlexPoint($post->getFlexPoint() + 1);
            /** @phpstan-ignore-next-line */
            $post->getAuthor()->setTotalFlex($post->getAuthor()->getTotalFlex() + 1);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('post_index');
    }
}
