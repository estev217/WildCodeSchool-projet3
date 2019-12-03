<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChecklistItemRepository")
 */
class ChecklistItem
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserChecklist", mappedBy="checklistItem", orphanRemoval=true)
     */
    private $userChecklists;

    public function __construct()
    {
        $this->userChecklists = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|UserChecklist[]
     */
    public function getUserChecklists(): Collection
    {
        return $this->userChecklists;
    }

    public function addUserChecklist(UserChecklist $userChecklist): self
    {
        if (!$this->userChecklists->contains($userChecklist)) {
            $this->userChecklists[] = $userChecklist;
            $userChecklist->setChecklistItem($this);
        }

        return $this;
    }

    public function removeUserChecklist(UserChecklist $userChecklist): self
    {
        if ($this->userChecklists->contains($userChecklist)) {
            $this->userChecklists->removeElement($userChecklist);
            // set the owning side to null (unless already changed)
            if ($userChecklist->getChecklistItem() === $this) {
                $userChecklist->setChecklistItem(null);
            }
        }

        return $this;
    }
}
