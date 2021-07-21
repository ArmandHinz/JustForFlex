<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MessageType;

/**
 * @Route("/message", name="message_")
 */
class MessageController extends AbstractController
{

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('team_show', ["id" => $message->getTeam()->getId()]);
        }

        return $this->render('message/admin/edit.html.twig', ["form" => $form->createView(), 'message' => $message]);
    }

    /**
     * Creat a new form in order to delete a comment
     *
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Message $message): response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->redirectToRoute('team_show', ["slug" => $message->getTeam()->getId()]);
    }
}
