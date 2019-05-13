<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;
use App\Entity\Story;
use App\Entity\Chapter;

class ValidateChapterCommand extends ContainerAwareCommand
{
    protected function configure(){
        $this
            ->setName('validate-chapter')
            ->setDescription('Validate chapter and restore votes.')
            ->setHelp('This command allows you to validate the chapter with the highest number of votes and 
                        reset the number of votes available.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $em = $this->getContainer()->get('doctrine')->getManager();
        $members = $em
            ->getRepository(User::class)
            ->findAll();

        $stories = $em
            ->getRepository(Story::class)
            ->getStoriesInProgress();

        foreach($members as $member){
            $member->setVotesNumber(3);
            $em->flush();
        }

        foreach($stories as $story){
            $currentChapter = $story->getChapterNumber() + 1;
            $chapters = $em
                ->getrepository(Chapter::class)
                ->getMostVoted($story->getId(), $currentChapter)
            ;
            if($chapters !== null){
                $chapters->setValidated(true);
                $story->setchapterNumber($story->getchapterNumber() + 1);
                $em->flush();
            }
        }

        $output->writeln('Votes number reset and chapters validated');
    }
}