<?php

namespace App\Controller;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();
            $this->addFlash('notification', "Le profil a bien été modifié !");
        }
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' =>$form->createView(),
        ]);
    }
}
