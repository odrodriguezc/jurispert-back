<?php

namespace App\Controller;

use App\Entity\PasswordUpdateKey;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\UserType;
use App\Repository\PasswordUpdateKeyRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, MailerInterface $mailer, PasswordUpdateKeyRepository $passwordUpdateKeyRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $passwordUpdateKey = new PasswordUpdateKey();
            $passwordUpdateKey
                ->setCreatedAt(new DateTime())
                ->setKeyCode(random_int(0000, 9999999));
            $user->setPasswordUpdateKey($passwordUpdateKey);

            $entityManager->persist($user);
            $entityManager->flush();



            //TODO: Envoyer un mail à l'utilisateur avec la keypasswordupdate
            $email =  new TemplatedEmail();

            $email->subject('Creation du mot de passe')
                ->htmlTemplate('emails/passwordUpdate.html.twig')
                ->from('notifications@jurispert.com')
                ->to($user->getEmail())
                ->context([
                    'id' => (string) $user->getId(),
                    'key' => (string) $user->getPasswordUpdateKey()->getKeyCode(),
                    'name' => (string) $user->getLastName()
                ]);;

            $mailer->send($email);
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {

        if ($this->isGranted('USER_VIEW', $user) === false) {

            $this->addFlash('danger', "Vous n'avez pas les droits");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {

        if ($this->isGranted('USER_EDIT', $user) === false) {

            $this->addFlash('danger', "Vous n'avez pas les droits");

            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/edit-pass/{id}", name="user_editPassword", methods={"GET","POST"})
     */
    public function editPassword(Request $request, User $user): Response
    {

        if (!$request->query->has('key') || $request->query->get('key') !== $user->getPasswordUpdateKey()->getKeyCode()) {
            die('access denied');
        }

        if ($user->getPasswordUpdateKey()->getCreatedAt()->diff(new \DateTime())->days > 10) {
            die('Le token a expiré. COnsultez l\'admin');
        }

        $form = $this->createForm(PasswordUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/editPassword.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
