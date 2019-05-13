<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /** 
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $imageName;

    /**
     * @var File|null
     *  @Assert\Image(
     *      mimeTypes="image/jpeg"
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min=3, 
     *      minMessage="Le pseudo choisit doit faire au minimum {{ limit }} caractères."
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min=6,
     *      max=20, 
     *      minMessage="Le mot de passe doit faire au minimum {{ limit }} caractères.", 
     *      maxMessage="Le mot de passe doit faire au maximum {{ limit }} caractères."
     * )
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Story", mappedBy="author")
     */
    private $stories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chapter", mappedBy="author")
     */
    private $chapters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message = "L'adresse Email '{{ value }}' n'est pas une adresse valide.",
     *      checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $votesReceived;

    /**
     * @ORM\Column(type="integer")
     */
    private $votesNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @var \DateTime|null
     */
    private $updatedAt;

    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \Datetime();
        $this->votesReceived = 0;
        $this->votesNumber = 3;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * @return Collection|Story[]
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): self
    {
        if (!$this->stories->contains($story)) {
            $this->stories[] = $story;
            $story->setAuthor($this);
        }

        return $this;
    }

    public function removeStory(Story $story): self
    {
        if ($this->stories->contains($story)) {
            $this->stories->removeElement($story);
            // set the owning side to null (unless already changed)
            if ($story->getAuthor() === $this) {
                $story->setAuthor(null);
            }
        }

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
            $chapter->setAuthor($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->contains($chapter)) {
            $this->chapters->removeElement($chapter);
            // set the owning side to null (unless already changed)
            if ($chapter->getAuthor() === $this) {
                $chapter->setAuthor(null);
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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getVotesReceived(): ?int
    {
        return $this->votesReceived;
    }

    public function setVotesReceived(int $votesReceived): self
    {
        $this->votesReceived = $votesReceived;

        return $this;
    }

    public function getVotesNumber(): ?int
    {
        return $this->votesNumber;
    }

    public function setVotesNumber(int $votesNumber): self
    {
        $this->votesNumber = $votesNumber;

        return $this;
    }

    public function incrementVotesReceived(){
        $this->votesReceived = $this->votesReceived + 1;
        return $this;
    }

    public function decrementVotesNumber(){
        $this->votesNumber = $this->votesNumber -1;
        return $this;
    }

    /**
     * @param File|UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
