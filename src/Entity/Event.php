<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nbr_vote;

    #[ORM\Column(type: 'boolean')]
    private $is_archived;

    #[ORM\Column(type: 'date')]
    private $date_end_vote;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToMany(targetEntity: GroupMail::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: "groupMailByEvent")]
    private $groupMails;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: OptionDateEvent::class)]
    private $optionDateEvent;

    #[ORM\ManyToOne(targetEntity: CategotyEvent::class, inversedBy: 'events')]
    private $categoryEvent;

    public function __construct()
    {
        $this->groupMails = new ArrayCollection();
        $this->optionDateEvent = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbrVote(): ?string
    {
        return $this->nbr_vote;
    }

    public function setNbrVote(?string $nbr_vote): self
    {
        $this->nbr_vote = $nbr_vote;

        return $this;
    }

    public function isIsArchived(): ?bool
    {
        return $this->is_archived;
    }

    public function setIsArchived(bool $is_archived): self
    {
        $this->is_archived = $is_archived;

        return $this;
    }

    public function getDateEndVote(): ?\DateTimeInterface
    {
        return $this->date_end_vote;
    }

    public function setDateEndVote(\DateTimeInterface $date_end_vote): self
    {
        $this->date_end_vote = $date_end_vote;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, GroupMail>
     */
    public function getGroupMails(): Collection
    {
        return $this->groupMails;
    }

    public function addGroupMail(GroupMail $groupMail): self
    {
        if (!$this->groupMails->contains($groupMail)) {
            $this->groupMails[] = $groupMail;
        }

        return $this;
    }

    public function removeGroupMail(GroupMail $groupMail): self
    {
        $this->groupMails->removeElement($groupMail);

        return $this;
    }

    /**
     * @return Collection<int, OptionDateEvent>
     */
    public function getOptionDateEvent(): Collection
    {
        return $this->optionDateEvent;
    }

    public function addOptionDateEvent(OptionDateEvent $optionDateEvent): self
    {
        if (!$this->optionDateEvent->contains($optionDateEvent)) {
            $this->optionDateEvent[] = $optionDateEvent;
            $optionDateEvent->setEvent($this);
        }

        return $this;
    }

    public function removeOptionDateEvent(OptionDateEvent $optionDateEvent): self
    {
        if ($this->optionDateEvent->removeElement($optionDateEvent)) {
            // set the owning side to null (unless already changed)
            if ($optionDateEvent->getEvent() === $this) {
                $optionDateEvent->setEvent(null);
            }
        }

        return $this;
    }

    public function getCategoryEvent(): ?CategotyEvent
    {
        return $this->categoryEvent;
    }

    public function setCategoryEvent(?CategotyEvent $categoryEvent): self
    {
        $this->categoryEvent = $categoryEvent;

        return $this;
    }
}
