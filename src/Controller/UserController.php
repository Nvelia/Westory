<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
//use Twig\Environment;
use App\Entity\User;
use App\Entity\Story;
use App\Entity\Chapter;
use App\Form\UserType;
use App\Form\ChapterType;
use App\Form\ChapterSelectType;
use App\Form\ChangePasswordType;
use App\Form\AvatarType;
use App\Form\ForgottenPassType;

class UserController extends AbstractController{

    /**
	 * @var ObjectManager
	 */
	private $em;

	public function __construct(ObjectManager $em){
		$this->em = $em;
	}

    public function add(Request $request){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
			$this->em->flush();
            $request->getSession()->getFlashBag()->add('info', "Inscription réussie. Vous pouvez vous connecter");
			return $this->redirectToRoute('home');
        }

        return $this->render('member/registration.html.twig', array(
			'form'		=> $form->createView(),
		));
        
	}

    public function viewMemberArea(Request $request, PaginatorInterface $paginator){
		$this->denyAccessUnlessGranted('ROLE_USER');

		$user = $this->getUser();
		$userPosts = [];
		$form = $this->createForm(ChapterSelectType::class);
		$formAvatar = $this->createForm(AvatarType::class);

		$formAvatar->handleRequest($request);
		if($formAvatar->isSubmitted() && $formAvatar->isValid()){
			$user->setImageFile($formAvatar->get('imageFile')->getData());
			$this->em->flush();

			$request->getSession()->getFlashBag()->add('info', "Avatar modifié");
			return $this->redirectToRoute('user.area');
		}


		$form->handleRequest($request);
		if($form->isSubmitted()){
			$userPosts = $this
				->em
				->getRepository(Chapter::class)
				->getSelectedPosts(
					$user,
					$form->get('story')->getData(),
					$form->get('isValid')->getData()
			);
		}

		$userStories = 
			$paginator->paginate(
				$this->em->getRepository(Story::class)->getUserStories($user), 
				$request->query->getInt('page', 1),
				5,
				array('wrap-queries'=>true)
		);
		
		$stories = $this
			->em
			->getRepository(Story::class)
			->findAll();

		return $this->render('member/memberArea.html.twig', array(
				'userStories'			=> $userStories,
				'userPosts'				=> $userPosts,
				'form'					=> $form->createView(),
				'formAvatar'			=> $formAvatar->createView()
			));
	}

	public function resetPassword(Request $request){
		$this->denyAccessUnlessGranted('ROLE_USER');

		$em = $this->getDoctrine()->getManager();
		$member = $em
			->getRepository(User::class)
			->findOneBy(array('username' => $this->getUser()->getUsername()
		));

		$oldPassword = $this->getUser()->getPassword();
		$form = $this->createForm(ChangePasswordType::class, $member);
		
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        	if (password_verify($form->get('oldPassword')->getData(), $oldPassword )){
				$em->flush();
				$request->getSession()->getFlashBag()->add('info', "Mot de passe modifié");
				return $this->redirectToRoute('user.area');
			}
			else{
				$request->getSession()->getFlashBag()->add('info', "Mot de passe incorrect");
			}
		}

		return $this->render('member/resetPass.html.twig', array(
			'form'		=> $form->createView(),
		));
	}

	public function viewMemberPage($member, Request $request, PaginatorInterface $paginator){

		if($member === null){
			throw new NotFoundHttpException("Ce membre n'a pas été trouvé.");
		}
		$user = $this->getUser();

		$memberPosts = [];
		$formOrder = $this->createForm(ChapterSelectType::class);

		$memberInfo = $this
			->em
			->getRepository(User::class)
			->findOneBy(array('username' => $member));

		if($user == $memberInfo){
			return $this->redirectToRoute('user.area');
		}

		$memberStories =
			$paginator->paginate(
				$this->em->getRepository(Story::class)->getUserStories($memberInfo), 
				$request->query->getInt('page', 1),
				5,
				array('wrap-queries'=>true)
		);

		$formOrder->handleRequest($request);
		if($formOrder->isSubmitted()){
			$memberPosts = $this
				->em
				->getRepository(Chapter::class)
				->getSelectedPosts(
					$memberInfo,
					$formOrder->get('story')->getData(),
					$formOrder->get('isValid')->getData()
				);
		}

		return $this->render('/member/memberPage.html.twig', array(
				'memberInfo'				=> $memberInfo,
				'memberStories'				=> $memberStories,
				'memberPosts'				=> $memberPosts,
				'formOrder'					=> $formOrder->createView(),
			));
	}

	public function forgottenPassword(Request $request){
		$user = new User;
		$form = $this->createForm(ForgottenPassType::class, $user);

		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
        	$userValid = $em
        		->getRepository(User::class)
				->getUserValid($user)
			;

        	if($userValid !== null){
        		$mailer = $this->get('mailer'); 

				$message = (new \Swift_Message('Demande de réinitialisation du mot de passe'))
			        ->setFrom("infos.ocnv@gmail.com")
			        ->setTo($userValid->getEmail())
			        ->setBody(
			            $this->renderView(
			                '/emails/resetPassword.html.twig',
			                array(	'name' 		=> $userValid->getUsername(),
			            			'member'	=> $userValid)
			            ),
			            'text/html'
			        );

		    	$mailer->send($message);
		    	$request->getSession()->getFlashBag()->add('info', "Un Email de réinitialisation du mot de passe a été envoyé.");
        	}
        	else{
        		$request->getSession()->getFlashBag()->add('info', "Aucun membre correspondant.");
        	}

        	
			return $this->redirectToRoute('home');
        }

		return $this->render('member/forgottenPass.html.twig', array(
			'form'					=> $form->createView(),
		));
	}

}