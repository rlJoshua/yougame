<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @Route("/list_user", name="list_user")
     */
    public function index()
    {
        $user = $this->userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $user
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
                return $this->redirectToRoute('list_user');
            } else {
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('user/form.html.twig', [
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route("/user/{id}", name="show_user")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function showUser(int $id, Request $request){
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();
        }
        return $this->render('user/view.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete_user/{id}", name="delete_user")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="No access! Get out!")
     * @ParamConverter("user", options={"mapping"={"id"="id"}})
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteGame(User $user){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($user);
        $entityManger->flush();

        return $this->redirectToRoute("list_user");
    }

}
