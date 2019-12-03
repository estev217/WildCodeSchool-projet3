<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserChecklistRepository")
 */
class UserChecklist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkedDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChecklistItem", inversedBy="userChecklists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $checklistItem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userChecklists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckedDate(): ?\DateTimeInterface
    {
        return $this->checkedDate;
    }

    public function setCheckedDate(\DateTimeInterface $checkedDate): self
    {
        $this->checkedDate = $checkedDate;

        return $this;
    }

    public function getChecklistItem(): ?ChecklistItem
    {
        return $this->checklistItem;
    }

    public function setChecklistItem(?ChecklistItem $checklistItem): self
    {
        $this->checklistItem = $checklistItem;

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
}
