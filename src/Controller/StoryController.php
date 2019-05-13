<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Story;
use App\Form\StoryType;
use App\Entity\Chapter;
use App\Form\ChapterType;
use App\Entity\Comment;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;


class StoryController extends AbstractController{

    /**
	 * @var ObjectManager
	 */
    private $em;

    public function __construct(ObjectManager $em){
        $this->em = $em;
    }

    public function add(Request $request){
        $this->denyAccessUnlessGranted('ROLE_USER');

        $story = new Story();     
        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $story->setAuthor($user);
            $chapter = $form->get('chapters')->getData();
            $chapter->setStory($story);
            $chapter->setAuthor($user);
            $chapter->setValidated(true);
            $chapter->setChapterNumber(1);
            $this->em->persist($story);
            $this->em->persist($chapter);
            $this->em->flush();

            $request->getSession()->getFlashBag()->add('info', "Histoire publiée.");
            return $this->redirectToRoute('story.view', array('id' => $story->getId()) );
        }

        return $this->render('story/add.html.twig', array(
            'form'  => $form->createView(),
        ));
    }

    public function view(Story $story, PaginatorInterface $paginator, Request $request){

        if($story === null){
			throw new NotFoundHttpException("L'histoire n'a pas été trouvée.");
        }
        
        $user = $this->getUser();
        $currentChapter = $story->getChapterNumber() + 1;
        $intro = $this
            ->em
            ->getRepository(Chapter::class)
            ->getIntro($story)
        ;

        if($story === null){
			throw new NotFoundHttpException("L'histoire n'a pas été trouvée.");
        }
        
        $comments = $this
            ->em
            ->getRepository(Comment::class)
            ->findBy(array('story' => $story))
        ;

        $posts = $this
            ->em
            ->getRepository(Chapter::class)
            ->findBy(array('story' => $story))
        ;

        $postsValid = 
            $paginator->paginate(
                $this->em->getRepository(Chapter::class)->getChaptersValid($story), 
                $request->query->getInt('page', 1),
                4,
                array('wrap-queries'=>true)
        );

        $currentPosts = $this
            ->em
			->getRepository(Chapter::class)
			->getCurrentPosts($story, $currentChapter)
		;

        return $this->render('story/view.html.twig', array(
			'story' 			=> $story,
            'posts'				=> $posts,
            'postsValid'        => $postsValid,
            'comments'          => $comments,
            'intro'             => $intro,
            'currentPosts'      => $currentPosts,
		));
    }

    public function viewInProgress(Request $request, PaginatorInterface $paginator){
        
        $stories = 
            $paginator->paginate(
                $this->em->getRepository(Story::class)->getInProgressStories(), 
                $request->query->getInt('page', 1),
                5,
                array('wrap-queries'=>true)
        );


        return $this->render('story/inProgress.html.twig', array(
			'stories' 		=> $stories,
		));	

    }

    public function viewFinished(Request $request, PaginatorInterface $paginator){

        $stories = 
            $paginator->paginate(
                $this->em->getRepository(Story::class)->getFinishedStories(), 
                $request->query->getInt('page', 1),
                5,
                array('wrap-queries'=>true)
        );
        
        return $this->render('story/finished.html.twig', array(
            'stories' 		=> $stories,
        ));
    }



}