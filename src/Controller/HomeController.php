<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     *
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->redirectToRoute('jmose_command_scheduler_list');
    }
}