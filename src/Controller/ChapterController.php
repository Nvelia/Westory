<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Chapter;
use App\Entity\Story;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


class ChapterController extends AbstractController{

    /**
	 * @var ObjectManager
	 */
	private $em;


    public function __construct(ObjectManager $em){
        $this->em = $em;
    }

    public function delete(Request $request, Chapter $chapter){
        $this->denyAccessUnlessGranted('ROLE_USER');

        if($chapter === null){
			throw new NotFoundHttpException("Le chapitre n'a pas été trouvé.");
        }
        $page = ceil($chapter->getStory()->getChapterNumber() / 4);
        $user = $this->getUser();

        if($user->getUsername() == $chapter->getAuthor()){
			$this->em->remove($chapter);
			$this->em->flush();

			$request->getSession()->getFlashBag()->add('info', "Chapitre proposé, supprimé.");
			return $this->redirectToRoute('story.view', array(
                'id' => $chapter->getStory()->getId(),
                'page' => $page
            ));
        }
        else{
			$request->getSession()->getFlashBag()->add('info', "Vous n'avez pas l'autorisation d'effectuer cette action");
		}
    }

    public function vote(Request $request, Chapter $chapter){
        $this->denyAccessUnlessGranted('ROLE_USER');

        if($chapter === null){
			throw new NotFoundHttpException("Le chapitre n'a pas été trouvé.");
        }

        $page = ceil($chapter->getStory()->getChapterNumber() / 4);
        $user = $this->getUser();

        if($user->getVotesNumber() > 0){
			$author = $chapter->getAuthor();
			$author->incrementVotesReceived();
            $chapter->setVotes($chapter->getVotes() + 1);
            $user->decrementVotesNumber();
            $this->em->flush();
            $request->getSession()->getFlashBag()->add('info', "Votre vote a bien été pris en compte");
        }
        else{
			$request->getSession()->getFlashBag()->add('info', "Plus aucun vote disponible");
        }
        
        return $this->redirectToRoute('story.view', array(
            'id' => $chapter->getStory()->getId(),
            'page' => $page
        ));

    }

    public function report(Request $request, Chapter $chapter){
		if($chapter === null){
			throw new NotFoundHttpException("Le chapitre n'a pas été trouvé.");
		}

		$wsReportCookieName = 'reportChapter'.$chapter->getId();
		$response = new Response();

		if($request->cookies->has($wsReportCookieName)){
			$request->getSession()->getFlashBag()->add('info', "Vous avez déjà signalé cette publication.");
		}

		else{
			$cookie = new cookie($wsReportCookieName, 'exists', time() + (86400 * 30));
			$response->headers->setCookie($cookie);
			$response->send();

			$chapter->setReports($chapter->getReports() + 1);
			$this->em->flush();
			$request->getSession()->getFlashBag()->add('info', "Votre signalement a bien été pris en compte");
		}
		
		return $this->redirectToRoute('story.view', array(
            'id' => $chapter->getStory()->getId()
        ));
    }

    public function add(Request $request, Story $story){
        $this->denyAccessUnlessGranted('ROLE_USER');

		if($story === null){
			throw new NotFoundHttpException("L\'histoire n\'a pas été trouvée.");
        }
        
        $page = ceil($story->getChapterNumber() / 4);
               
		if ($request->isMethod('POST')){
            $chapter = new Chapter();
            $user = $this->getUser();

            $chapter->setAuthor($user);
            $chapter->setStory($story);
            $chapter->setChapterNumber($story->getChapterNumber() +1);

            $chapter->setContent($request->request->get('_content'));
            $this->em->persist($chapter);
            $this->em->flush();

            $request->getSession()->getFlashBag()->add('info', "Chapitre proposé.");
            
            return $this->redirectToRoute('story.view', array(
                'id' => $story->getId(),
                'page' => $page
            ));
        }
    }

}