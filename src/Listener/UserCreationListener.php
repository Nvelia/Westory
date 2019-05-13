<?php

namespace App\Listener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Email\UserMailer;
use App\Entity\User;

class UserCreationListener{
    /**
     * @var UserMailer
     */
    private $userMailer;

    public function __construct(UserMailer $userMailer){
        $this->userMailer = $userMailer;
    }

    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if (!$entity instanceof User){
          return;
        }

        $this->userMailer->sendMail($entity);
    }
}
