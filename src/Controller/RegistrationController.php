<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     *
     * @param Request                   $request  La requete courante
     * @param GuardAuthenticatorHandler $guardHandler Le guard authenticator
     * @param LoginFormAuthenticator    $authenticator Le loginForm authenticator
     * @param AdminService              $adminService Le gestionnaire de service admin
     *
     * @return Response|null
     */
    public function register(Request $request, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, AdminService $adminService)
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
