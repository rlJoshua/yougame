<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
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
     * @Route("/game/{id}", name="show_game")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function showGame(int $id, Request $request){
        $game = $this->gameRepository->find($id);
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($game);
            $entityManger->flush();
        }
        return $this->render('game/view.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create_game", name="create_game")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @param Request $request
     * @return Response
     */
    public function createGame(Request $request): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();
            return $this->redirectToRoute('list_game');
        }

        return $this->render('game/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_game/{id}", name="delete_game")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @ParamConverter("game", options={"mapping"={"id"="id"}})
     * @param Game $game
     * @return RedirectResponse
     */
    public function deleteGame(Game $game){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($game);
        $entityManger->flush();

        return $this->redirectToRoute("list_game");
    }

}
