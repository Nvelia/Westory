<?php

namespace App\Repository;

use App\Entity\Story;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;

/**
 * @method Story|null find($id, $lockMode = null, $lockVersion = null)
 * @method Story|null findOneBy(array $criteria, array $orderBy = null)
 * @method Story[]    findAll()
 * @method Story[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Story::class);
    }

	public function getInProgressStories(){
        return $this->createQueryBuilder('s')
            ->having('s.chapterNumber < s.chapterLimit')
            ->orderby('s.createdAt', 'DESC')
            ->getQuery()
		;
    }
    
    public function getFinishedStories(){
        return $this->createQueryBuilder('s')
            ->having('s.chapterNumber = s.chapterLimit')
            ->orderby('s.createdAt', 'DESC')
            ->getQuery()
        ;
    }
    
    public function getUserStories(User $user){
        return $this->createQueryBuilder('s')
            ->where('s.author = :user')
                ->setParameter('user', $user)
            ->orderby('s.createdAt', 'DESC')
            ->getQuery()
		;
    }

    public function getStoriesInProgress(){
		return $this->createQueryBuilder('s')
			->having('s.chapterNumber < s.chapterLimit')
            ->getQuery()
            ->getResult();
			;
    }
	public function isSpam($user){
		$date = date('Y/m/d');
		return $this->createQueryBuilder('s')
			->where('s.author = :user')
				->setParameter('user', $user)
			->andWhere('DATE_FORMAT(s.createdAt, \'%Y/%m/%d\') = :date')
				->setParameter('date', $date)
			->orderBy('s.createdAt', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult()
        ;
	}
}
