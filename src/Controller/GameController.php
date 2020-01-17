<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    private $gameRepository;

    public function __construct(GameRepository $gameRepository){
        $this->gameRepository = $gameRepository;
    }
    /**
     * @Route("/game", name="list_game")
     */
    public function index()
    {
        $gameList = $this->gameRepository->findAll();
        return $this->render('game/list.html.twig', [
            'gameList' => $gameList,
        ]);
    }

    /**
     * @Route("/create_game", name="create_game")
     */

     public function createGame(Request $request): Response{

        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();       
            return $this->redirectToRoute('list_game');
        }

        return $this->render('game/index.html.twig', [
            'form' => $form->createView(),
        ]);
     }



}
