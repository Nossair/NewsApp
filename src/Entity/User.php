<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Event::class)]
    private $events;

    #[ORM\ManyToMany(targetEntity: OptionDateEvent::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: "Vote")]
    private $optionDateEvents;

    #[ORM\ManyToMany(targetEntity: GroupMail::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: "GroupMailsByUser")]
    private $groupMails;




    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->chose_date_event = new ArrayCollection();
        $this->choiceDateEvent = new ArrayCollection();
        $this->groupMails = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->optionDateEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OptionDateEvent>
     */
    public function getOptionDateEvents(): Collection
    {
        return $this->optionDateEvents;
    }

    public function addOptionDateEvent(OptionDateEvent $optionDateEvent): self
    {
        if (!$this->optionDateEvents->contains($optionDateEvent)) {
            $this->optionDateEvents[] = $optionDateEvent;
        }

        return $this;
    }

    public function removeOptionDateEvent(OptionDateEvent $optionDateEvent): self
    {
        $this->optionDateEvents->removeElement($optionDateEvent);

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



}
