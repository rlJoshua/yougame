<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Form\PlatformType;
use Doctrine\DBAL\Types\TextType;
use App\Repository\PlatformRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PlatformController extends AbstractController
{
    private $platformRepository;

    public function __construct(PlatformRepository $platformRepository)
    {
        $this->platformRepository = $platformRepository;
    }

    /**
     * @Route("/list_platform", name="list_platform")
     */
    public function index()
    {
        $platforms = $this->platformRepository->findAll();
        return $this->render('platform/index.html.twig', [
            "platforms" => $platforms
        ]);
    }

    /**
     * @Route("/create_platform", name="create_platform")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return Response
     */
    public function createPlatform(Request $request): Response{

        $platform = new Platform();
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($platform);
            $entityManager->flush();
            return $this->redirectToRoute('list_platform');
        }

        return $this->render('platform/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   /**
     * @Route("/platform/{id}", name="show_platform")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function show(int $id, Request $request){
        $platform = $this->platformRepository->find($id);
        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($platform);
            $entityManger->flush();
        }
        return $this->render('platform/view.html.twig', [
            'platform' => $platform,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_platform/{id}", name="delete_platform")
     * @IsGranted("ROLE_ADMIN")
     * @ParamConverter("platform", options={"mapping"={"id"="id"}})
     * @param Platform $platform
     * @return RedirectResponse
     */
    public function deletePlatform(Platform $platform){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($platform);
        $entityManger->flush();

        return $this->redirectToRoute("list_platform");
    }

}
