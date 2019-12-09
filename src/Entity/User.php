<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Position", inversedBy="users")
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="collaborators")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="manager")
     */
    private $collaborators;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="users")
     */
    private $residence;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Residence", inversedBy="users")
     */
    private $residencePilot;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Content", mappedBy="user")
     */
    private $contents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSkill", mappedBy="user", orphanRemoval=true)
     */
    private $userSkills;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserChecklist", mappedBy="user", orphanRemoval=true)
     */
    private $userChecklists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="user", orphanRemoval=true)
     */
    private $appointments;

    public function __construct()
    {
        $this->collaborators = new ArrayCollection();
        $this->contents = new ArrayCollection();
        $this->userSkills = new ArrayCollection();
        $this->userChecklists = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getMentor(): ?string
    {
        return $this->mentor;
    }

    public function setMentor(string $mentor): self
    {
        $this->mentor = $mentor;

        return $this;
    }

    public function getReferent(): ?string
    {
        return $this->referent;
    }

    public function setReferent(string $referent): self
    {
        $this->referent = $referent;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getManager(): ?self
    {
        return $this->manager;
    }

    public function setManager(?self $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(self $collaborator): self
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators[] = $collaborator;
            $collaborator->setManager($this);
        }

        return $this;
    }

    public function removeCollaborator(self $collaborator): self
    {
        if ($this->collaborators->contains($collaborator)) {
            $this->collaborators->removeElement($collaborator);
            // set the owning side to null (unless already changed)
            if ($collaborator->getManager() === $this) {
                $collaborator->setManager(null);
            }
        }

        return $this;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function setResidence(?Residence $residence): self
    {
        $this->residence = $residence;

        return $this;
    }

    public function getResidencePilot(): ?Residence
    {
        return $this->residencePilot;
    }

    public function setResidencePilot(?Residence $residencePilot): self
    {
        $this->residencePilot = $residencePilot;

        return $this;
    }

    /**
     * @return Collection|Content[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setUser($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getUser() === $this) {
                $content->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserSkill[]
     */
    public function getUserSkills(): Collection
    {
        return $this->userSkills;
    }

    public function addUserSkill(UserSkill $userSkill): self
    {
        if (!$this->userSkills->contains($userSkill)) {
            $this->userSkills[] = $userSkill;
            $userSkill->setUser($this);
        }

        return $this;
    }

    public function removeUserSkill(UserSkill $userSkill): self
    {
        if ($this->userSkills->contains($userSkill)) {
            $this->userSkills->removeElement($userSkill);
            // set the owning side to null (unless already changed)
            if ($userSkill->getUser() === $this) {
                $userSkill->setUser(null);
            }
        }

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
            $userChecklist->setUser($this);
        }

        return $this;
    }

    public function removeUserChecklist(UserChecklist $userChecklist): self
    {
        if ($this->userChecklists->contains($userChecklist)) {
            $this->userChecklists->removeElement($userChecklist);
            // set the owning side to null (unless already changed)
            if ($userChecklist->getUser() === $this) {
                $userChecklist->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setUser($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getUser() === $this) {
                $appointment->setUser(null);
            }
        }

        return $this;
    }
}
