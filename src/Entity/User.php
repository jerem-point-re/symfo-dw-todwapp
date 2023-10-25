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
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Lists::class)]
    private Collection $lists;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->lists = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Lists>
     */
    public function getListss(): Collection
    {
        return $this->lists;
    }

    public function addLists(Lists $listing): static
    {
        if (!$this->lists->contains($listing)) {
            $this->lists->add($listing);
            $listing->setUser($this);
        }

        return $this;
    }

    public function removeLists(Lists $listing): static
    {
        if ($this->lists->removeElement($listing)) {
            // set the owning side to null (unless already changed)
            if ($listing->getUser() === $this) {
                $listing->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lists>
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(Lists $list): static
    {
        if (!$this->lists->contains($list)) {
            $this->lists->add($list);
            $list->setUser($this);
        }

        return $this;
    }

    public function removeList(Lists $list): static
    {
        if ($this->lists->removeElement($list)) {
            // set the owning side to null (unless already changed)
            if ($list->getUser() === $this) {
                $list->setUser(null);
            }
        }

        return $this;
    }
}
