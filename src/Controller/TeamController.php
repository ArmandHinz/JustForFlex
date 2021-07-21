<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Team;
use App\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\MessageType;

/**
 * @Route("/team", name="team_")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'users' => $users
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($team);
            $entityManager->flush();
            return $this->redirectToRoute('team_index');
        }
        return $this->render('team/admin/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/addMate/{id}", name="mate")
     */
    public function addMate(Team $team): Response
    {
        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();

        $slot1 = $team->getSlot1();
        $slot2 = $team->getSlot2();
        $slot3 = $team->getSlot3();
        $slot4 = $team->getSlot4();
        $slot5 = $team->getSlot5();

        if ($slot1 == null) {
            $team->setSlot1($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } elseif ($slot2 == null) {
            $team->setSlot2($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } elseif ($slot3 == null) {
            $team->setSlot3($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } elseif ($slot4 == null) {
            $team->setSlot4($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        } elseif ($slot5 == null) {
            $team->setSlot5($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }


        return $this->redirectToRoute('team_index');
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Team $team, Request $request): Response
    {

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($this->getUser());
            $message->setDate(new \DateTime());
            $message->setTeam($team);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('team_show', ["id" => $team->getId()]);
        }

        return $this->render(
            'team/show.html.twig',
            [
                'team' => $team,
                "form" => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Team $team): response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($team);
        $entityManager->flush();
        return $this->redirectToRoute('team_index');
    }
}
