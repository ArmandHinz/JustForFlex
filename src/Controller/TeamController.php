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
use App\Repository\TeamRepository;
use App\Form\SearchTeamType;
use App\Service\Slugify;

/**
 * @Route("/team", name="team_")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request, TeamRepository $teamRepository): Response
    {

        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $form = $this->createForm(SearchTeamType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $teams = $teamRepository->findSearch($search['videoGame']->getName(), $search['search']);
        } else {
            $teams = $teamRepository->findAll();
        }

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($team->getName());
            $team->setSlug($slug);
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
     * @Route("/{slug}", name="show")
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
            return $this->redirectToRoute('team_show', ["slug" => $team->getSlug()]);
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
