<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PositionRepository")
 */
class Position
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="position")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->users2 = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPosition($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPosition() === $this) {
                $user->setPosition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers2(): Collection
    {
        return $this->users2;
    }

    public function addUsers2(User $users2): self
    {
        if (!$this->users2->contains($users2)) {
            $this->users2[] = $users2;
            $users2->setPosition($this);
        }

        return $this;
    }

    public function removeUsers2(User $users2): self
    {
        if ($this->users2->contains($users2)) {
            $this->users2->removeElement($users2);
            // set the owning side to null (unless already changed)
            if ($users2->getPosition() === $this) {
                $users2->setPosition(null);
            }
        }

        return $this;
    }
}
