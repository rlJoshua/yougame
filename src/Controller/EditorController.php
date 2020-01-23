<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Editor;
use App\Form\GameType;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        return $this->render('editor/index.html.twig', [
            'editorList' => $editorList,
        ]);
    }

    /**
     * @Route("/create_editor", name="create_editor")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @param Request $request
     * @return Response
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

        return $this->render('editor/form.html.twig', [
            'form' => $form->createView(),
        ]);
     }
      /**
     * @Route("/editor/{id}", name="show_editor")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function showEditor(int $id, Request $request){
        $editor = $this->editorRepository->find($id);
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($editor);
            $entityManger->flush();
        }
        return $this->render('editor/view.html.twig', [
            'editor' => $editor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_editor/{id}", name="delete_editor")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @ParamConverter("editor", options={"mapping"={"id"="id"}})
     * @param Editor $editor
     * @return RedirectResponse
     */
    public function deleteEditor(Editor $editor){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($editor);
        $entityManger->flush();

        return $this->redirectToRoute("list_editor");
    }


     

}
