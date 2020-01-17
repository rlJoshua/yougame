<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Editor;
use App\Form\GameType;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditorController extends AbstractController
{
    private $editorRepository;

    public function __construct(EditorRepository $editorRepository){
        $this->editorRepository = $editorRepository;
    }
    /**
     * @Route("/list_editor", name="list_editor")
     */
    public function index()
    {
        $editorList = $this->editorRepository->findAll();
        //dd($editorList);
        return $this->render('editor/list.html.twig', [
            'editorList' => $editorList,
        ]);
    }

    /**
     * @Route("/create_editor", name="create_editor")
     */
    public function createEditor(Request $request): Response{

        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editor);
            $entityManager->flush();       
            return $this->redirectToRoute('list_editor');
        }

        return $this->render('editor/index.html.twig', [
            'form' => $form->createView(),
        ]);
     }

}
