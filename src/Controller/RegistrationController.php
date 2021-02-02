<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * Class RegistrationController
 *
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * Permet d'enregrister un admin
     *
     * @Route("/register", name="register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        AdminService $adminService): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $newAdmin = $adminService->createAdmin($form->getData());
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }

            return $guardHandler->authenticateUserAndHandleSuccess(
                $newAdmin,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
