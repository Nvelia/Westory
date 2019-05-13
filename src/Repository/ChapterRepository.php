<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use App\Entity\Story;
use App\Entity\User;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function getIntro($story){
        return $this->createQueryBuilder('i')
            ->where('i.story = :story')
                ->setParameter('story', $story)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getCurrentPosts(Story $story, $chapter){
		return $this->createQueryBuilder('p')
			->where('p.story = :story')
				->setParameter('story', $story)
			->andWhere('p.chapterNumber = :chapter')
				->setParameter('chapter', $chapter)
			->getQuery()
			->getResult()
		;
    }
    
    public function getSelectedPosts(User $user, Story $story, $validated){
		return $this->createQueryBuilder('p')
			->where('p.author = :user')
				->setParameter('user', $user)
			->andWhere('p.story = :story')
				->setParameter('story', $story)
			->andWhere('p.validated = :validated')
				->setParameter('validated', $validated)
			->orderBy('p.createdAt', 'DESC')
			->getQuery()
			->getResult()
		;
    }
    
    public function getMostVoted($story, $currentChapter){
		return $this->createQueryBuilder('p')
			->where('p.story = :story')
				->setParameter('story', $story)
			->andWhere('p.chapterNumber = :chapter')
				->setParameter('chapter', $currentChapter)
			->having('p.reports < 10')
			->orderBy('p.votes', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult()
		;
	}

   public function getChaptersValid(Story $story){
        return $this->createQueryBuilder('p')
            ->where('p.story = :story')
                ->setParameter('story', $story)
            ->andWhere('p.validated = TRUE')
            ->getQuery()
        ;
   }
}
