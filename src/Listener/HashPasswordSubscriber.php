<?php

namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class HashPasswordSubscriber implements EventSubscriber{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents(){
        return [
            'prePersist',
            'preUpdate'
        ];
    }


    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if (!$entity instanceof User){
          return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();

        if (!is_callable([$entity, 'getPassword'])){
            return;
        }

        if(strlen($entity->getPassword()) > 20){
            return;
        }

        $this->encodePassword($entity);

        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity){
        if(!$entity->getPassword()){
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword(
          $entity,
          $entity->getPassword()
        );

        $entity->setPassword($encoded);
    }


}
