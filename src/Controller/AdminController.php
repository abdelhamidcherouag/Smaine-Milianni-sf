<?php

namespace App\Controller;


use App\Entity\Token;
use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthentificatorAuthenticator;
use App\Services\TokenSendler;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function admin(UserRepository $userRepository){

        return $this->render('admin/admin.html.twig',[
            'users' => $userRepository->findAll(),

        ]);
    }

    /**
     * @Route("/admin/delete", name="delete_user")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deleteUser(User $user, EntityManagerInterface $entityManager,UserRepository $userRepository){

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Utilisateur supprimer'
        );

        return $this->render('admin/admin.html.twig',[
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
 * @Route("/login", name="app_login")
 */
    public function login(AuthenticationUtils $authenticationUtils):Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lasteUserName = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lasteUserName, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param LoginFormAuthentificatorAuthenticator $loginFormAuthentificatorAuthenticator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenSendler $tokenSendler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registration(Request$request, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthentificatorAuthenticator $loginFormAuthentificatorAuthenticator, UserPasswordEncoderInterface $passwordEncoder, TokenSendler $tokenSendler)
    {
        $user = new User();

        $form = $this->createForm(InscriptionType::class, $user);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){
            $passwordEncoded = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncoded);
            $user->setRoles(['ROLE_ADMIN']);

            $token = new Token($user);
            $manager->persist($token);
            $manager->flush();

            $tokenSendler->sendToken($user, $token);
            $this->addFlash(
                'notice',
                'Un email de confirmation a ete envoyév veuillez cliquer sur le lien'
            );

            return $this->redirectToRoute('home');
        }


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/confirmation/{Value}", name="token_validation")
     * @param TokenRepository $tokenRepository
     * @param Token $token
     * @param EntityManagerInterface $manager
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param LoginFormAuthentificatorAuthenticator $loginFormAuthentificatorAuthenticator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenSendler $tokenSendler
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function validateToken(Token $token, EntityManagerInterface $manager, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthentificatorAuthenticator $loginFormAuthentificatorAuthenticator, UserPasswordEncoderInterface $passwordEncoder, TokenSendler $tokenSendler,Request $request)
    {
        $user = $token->getUser();

        if ($user->getEnable()){
            $this->addFlash(
                'notice',
                "Ce token est déja validé !"
            );

            return $this->redirectToRoute('home');
        }

        if ($token->isValid()){
            $user->setEnable(true);
            $manager->flush($user);
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthentificatorAuthenticator,
                'main'
            );
        }

        $manager->remove($token);
        $this->addFlash(
            'notice',
            'Le Token est expiré, Inscrivez-vous  à nouveau'
        );
        return $this->redirectToRoute('registration');
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }




}
