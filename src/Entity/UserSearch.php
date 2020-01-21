<?php


namespace App\Entity;

class UserSearch
{
    /**
     * @var Role|null
     */
    private $role;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * @param Role|null $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
