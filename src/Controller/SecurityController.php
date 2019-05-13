<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
   

    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $referer = $request->headers->get('referer');

        $request->getSession()->getFlashBag()->add('info', "Identifiant et/ou mot de passe incorrect.");
        return $this->render('pages/home.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }
}
