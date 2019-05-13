<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Validator\NoSpam;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoryRepository")
 * @UniqueEntity(fields="title", message="Une histoire existe déjà avec ce titre.")
 */
class Story
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @NoSpam()
     * @Assert\Length(
     *      min=2,
     *      max=100,
     *      minMessage="Le titre doit comporter au minimum {{ limit }} caractères.",
     *      maxMessage="Le titre peut comporter au maximum {{ limit }} caractères."
     * )
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="stories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min=10,
     *      max=50,
     *      minMessage="Cette valeur doit être supérieure ou égale à {{ limit }}.",
     *      maxMessage="Cette valeut doit être inférieure ou égale à {{ limit }}."
     * )
     */
    private $chapterLimit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="story")
     */
    private $chapters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="story")
     */
    private $comments;

    /**
     * @ORM\Column(type="integer")
     */
    private $chapterNumber;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->chapterNumber = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChapterLimit(): ?int
    {
        return $this->chapterLimit;
    }

    public function setChapterLimit(int $chapterLimit): self
    {
        $this->chapterLimit = $chapterLimit;

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setStory($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
            // set the owning side to null (unless already changed)
            if ($chapter->getStory() === $this) {
                $chapter->setStory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setStory($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getStory() === $this) {
                $comment->setStory(null);
            }
        }

        return $this;
    }

    public function getChapterNumber(): ?int
    {
        return $this->chapterNumber;
    }

    public function setChapterNumber(int $chapterNumber): self
    {
        $this->chapterNumber = $chapterNumber;

        return $this;
    }
}
