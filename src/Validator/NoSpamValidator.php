<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Story;

class NoSpamValidator extends ConstraintValidator{

	private $requestStack;
	private $em;
	private $user;

	public function __construct(RequestStack $requestStack, EntityManagerInterface $em, TokenStorageInterface $tokenStorage){
		$this->requestStack = $requestStack;
		$this->em 			= $em;
		$this->user 		= $tokenStorage->getToken()->getUser();
	}

	public function validate($value, Constraint $constraint){
	    $request = $this->requestStack->getCurrentRequest();

	    $isSpam = $this->em
	    	->getRepository(Story::class)
	    	->isSpam($this->user)
		;

	    if($isSpam !== null) {
	    	$this->context->addViolation($constraint->message);
	    }
	}

}