<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionDateEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionDateEventRepository::class)]
#[ApiResource]
class OptionDateEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $optionDate;

    #[ORM\Column(type: 'integer')]
    private $nbrVote;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'optionDateEvents')]
    #[ORM\JoinTable(name: "Vote")]
    private $users;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'optionDateEvent')]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionDate(): ?\DateTimeInterface
    {
        return $this->optionDate;
    }

    public function setOptionDate(\DateTimeInterface $optionDate): self
    {
        $this->optionDate = $optionDate;

        return $this;
    }

    public function getNbrVote(): ?int
    {
        return $this->nbrVote;
    }

    public function setNbrVote(int $nbrVote): self
    {
        $this->nbrVote = $nbrVote;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addOptionDateEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeOptionDateEvent($this);
        }

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
