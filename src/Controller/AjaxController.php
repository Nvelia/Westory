<?php
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Story;
use App\Repository\StoryRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
 
class AjaxController extends Controller
{
    public function updateData(Request $request)
    {
        $data = $request->get('input');
        $em = $this->getDoctrine()->getManager();
        $referer = $request->headers->get('referer');
        $previousRouteName = $this->generateUrl('story.finished', array(), UrlGeneratorInterface::ABSOLUTE_URL);
        $repository = $this
            ->getDoctrine()
            ->getRepository(Story::class);


        if($referer == $previousRouteName){
            $query = $repository->createQueryBuilder('s')
                ->where('s.title LIKE :data')
                ->setParameter('data', $data . '%')
                ->having('s. chapterLimit = s.chapterNumber')
                ->orderBy('s.title', 'ASC')
                ->getQuery();

            $results = $query->getResult();
        }
        else{
            $query = $repository->createQueryBuilder('s')
                ->where('s.title LIKE :data')
                ->setParameter('data', $data . '%')
                ->having('s. chapterLimit != s.chapterNumber')
                ->orderBy('s.title', 'ASC')
                ->getQuery();

            $results = $query->getResult();
        }

        $storyRep = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Story::class)
        ;
        
        $storyList = '<div id="matchList">';

        if($results == null ){
            $storyList = 'Aucune histoire trouvée.';
        }
        else{
            foreach ($results as $result) {

                $story = $repository->find($result->getId());
    
                $storyList .= '<div class="storyContainer">
                                <div class="storySearch">
                                    <div>
                                        <h4 class="storyTitle">
                                            <a href="../histoires-en-cours/'.$story->getId().'">'.$story->getTitle().'</a>
                                        </h4>
                                        <div>
                                            <em class="storyDate">
                                                Débutée le ' .$story->getCreatedAt()->format('d/m/Y').' par
                                            </em>
                                            <div class="authorInfos">
                                                <strong>
                                                    <a href="../espace-membre/'.$story->getAuthor()->getUsername().'">
                                                        '. $story->getAuthor()->getUsername().'
                                                    </a>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chapterProgress">
                                        <div class="bookmark">
                                            <div>Chap.</div>
                                            <div>'. $story->getChapterNumber() .'</div>
                                            <div>/</div>
                                            <div>'. $story->getChapterLimit() .'</div>
                                        </div>
                                    </div>
                                </div>
                                </div>';
            }
            $storyList .= '</div>';
        }
        
 
        $response = new JsonResponse();
        $response->setData(array('storyList' => $storyList));
        return $response;
    }
}