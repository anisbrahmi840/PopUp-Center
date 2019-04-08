<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserType;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    private $courseRepository;
    private  $objectManager;
    private $userRepository;
    public function __construct(CourseRepository $courseRepository, ObjectManager $objectManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
        $this->objectManager = $objectManager;


    }

    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }


    /**
     * @Route("/edit/image/{i}", name="edit_image", methods={"GET","POST"})
     */
    public function editImage(Request $request , int $i, UserRepository $userRepository)
    {
        $user = $userRepository->find($i);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdateAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fos_user_profile_show', [
                'id' => $user->getId(),
            ]);
        }


        return $this->render('users/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }






}
