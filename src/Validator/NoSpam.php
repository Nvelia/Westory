<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoSpam extends Constraint{

  	public $message = "Vous avez déjà publié une histoire aujourd'hui, réessayez demain.";

  	public function validatedBy(){
  		return 'nospam';
  	}
}