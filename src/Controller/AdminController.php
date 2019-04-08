<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, CourseRepository $courseRepository, UserRepository $userRepository)
    {
        $nbCourses =  count($courseRepository->findAll());
        $nbStudents = count($userRepository->findByRole($userRepository, '%"ROLE_STUDENT"%'));
        $nbProfessor = count($userRepository->findByRole($userRepository, '%"ROLE_PROFESSOR"%'));
        $nbAdmin = count($userRepository->findByRole($userRepository, '%"ROLE_ADMIN"%'));

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'nbCourses' => $nbCourses,
            'nbStudents' => $nbStudents,
            'nbProfessor' => $nbProfessor,
            'nbAdmin' => $nbAdmin,
        ]);
    }

    /**
     * @Route("/admin/adduser", name="adduser")
     */
    public function adduser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/adduser.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/courses", name="admin_courses")
     */
    public function allCourses(Request $request, CourseRepository $courseRepository)
    {
        $courses = $courseRepository->findAll();


        return $this->render('admin/courses.html.twig', [
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/admin/students", name="admin_students")
     */
    public function allstudents(Request $request, UserRepository $userRepository)
    {
        $students = $userRepository->findByRole($userRepository, '%"ROLE_STUDENT"%');


        return $this->render('admin/students.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/admin/professors", name="admin_professors")
     */
    public function allProfessors(Request $request, UserRepository $userRepository)
    {
        $professors = $userRepository->findByRole($userRepository, '%"ROLE_PROFESSOR"%');


        return $this->render('admin/professors.html.twig', [
            'professors' => $professors,
        ]);
    }

}
