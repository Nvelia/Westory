<?php

namespace App\Email;

use App\Entity\User;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class UserMailer extends \Twig_Extension{
    /**
    * @var \Swift_Mailer
    */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendMail(User $user){

        $message = (new \Swift_Message('Votre inscription sur Westory'))
            ->setFrom("infos@portfolio-nvelia.com", "Westory")
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/registration.html.twig',
                    array('name' => $user->getUsername())
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
