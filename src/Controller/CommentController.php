<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Entity\Story;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


class CommentController extends AbstractController{

    /**
	 * @var ObjectManager
	 */
	private $em;


    public function __construct(ObjectManager $em){
        $this->em = $em;
    }

    public function delete(Request $request, Comment $com){
        $this->denyAccessUnlessGranted('ROLE_USER');

		if($com === null){
			throw new NotFoundHttpException("Ce commentaire n'a pas été trouvé.");
        }
        
        $user = $this->getUser();

        if($user->getUsername() == $com->getAuthor() OR $user->getUsername() == $com->getStory()->getAuthor()){
			$em->remove($com);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', "Commentaire supprimé.");
			return $this->redirectToRoute('oc_westory_view_story', array(
                'id' => $com->getStory()->getId()
            ));
		}

		else{
			throw new NotFoundHttpException("Vous n'avez pas l'autorisation d'effectuer cette action");
		}
    }

    public function report(Request $request, Comment $com){
        
		if($com === null){
			throw new NotFoundHttpException("Ce commentaire n'a pas été trouvé.");
        }
        
        $wsReportComCookieName = 'reportCom'.$com->getId();
		$response = new Response();

		if($request->cookies->has($wsReportComCookieName)){
			$request->getSession()->getFlashBag()->add('info', "Vous avez déjà signalé ce commentaire.");
        }
        else{
			$cookie = new cookie($wsReportComCookieName, 'exists', time() + (86400 * 30));
			$response->headers->setCookie($cookie);
			$response->send();

			$com->setReports($com->getReports() + 1);
			$em->flush();
			$request->getSession()->getFlashBag()->add('info', "Votre signalement a bien été pris en compte");
		}
		
		return $this->redirectToRoute('story.view', array(
            'id' => $com->getStory()->getId()
        ));

	}
	
	public function add(Request $request, Story $story){
        $this->denyAccessUnlessGranted('ROLE_USER');

		if($story === null){
			throw new NotFoundHttpException("L\'histoire n\'a pas été trouvée.");
		}
               
		if ($request->isMethod('POST')){
            $comment = new Comment();
            $user = $this->getUser();

            $comment->setAuthor($user);
            $comment->setStory($story);

            $comment->setContent($request->request->get('_contentCom'));
            $this->em->persist($comment);
            $this->em->flush();
            
            $request->getSession()->getFlashBag()->add('info', "Commentaire publié.");
            return $this->redirectToRoute('story.view', array(
                'id' => $story->getId()
            ));
        }
    }

}