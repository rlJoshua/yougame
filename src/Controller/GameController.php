<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
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
        return $this->render('game/index.html.twig', [
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
            $this->addFlash('notification', "Le jeu a bien été modifié !");
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
            $title = $game->getTitle();
            $this->addFlash('notification', "Le jeu $title a bien été crée !");
            return $this->redirectToRoute('list_game');
        }

        return $this->render('game/form.html.twig', [
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
        $title = $game->getTitle();
        $this->addFlash('notification', "Le jeu $title a bien été supprimé !");
        return $this->redirectToRoute("list_game");
    }

    /**
     * @Route("/add_favorites/{id}", name="add_favorites")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param int $id
     * @return RedirectResponse
     */
    public function addFavorites(int $id){
        $game = $this->gameRepository->find($id);
        $user = $this->getUser();
        $user->addFavorite($game);
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->persist($user);
        $entityManger->flush();
        $this->addFlash('notification', "Ajouté aux favoris !");
        return $this->redirectToRoute("show_game", ['id' => $id]);
    }


    /**
     * @Route("/delete_favorites/{id}", name="delete_favorites")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteFavorites(int $id){
        $game = $this->gameRepository->find($id);
        $user = $this->getUser();
        $user->removeFavorite($game);
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->persist($user);
        $entityManger->flush();
        $this->addFlash('notification', "Supprimé des favoris !");
        return $this->redirectToRoute("show_game", ['id' => $id]);
    }
}
