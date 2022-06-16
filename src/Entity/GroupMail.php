<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupMailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupMailRepository::class)]
#[ApiResource]
class GroupMail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;


    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'groupMails')]
    #[ORM\JoinTable(name: "groupMailByEvent")]
    private $events;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groupMails')]
    #[ORM\JoinTable(name: "GroupMailsByUser")]

    private $users;



    public function __construct()
    {
        $this->events = new ArrayCollection();

        $this->users = new ArrayCollection();
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
            $event->addGroupMail($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeGroupMail($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function addUsers(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroupMail($this);
        }

    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeGroupMail($this);
        }

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroupMail($this);
        }

        return $this;
    }



}
