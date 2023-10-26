<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lists $lists = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
    
    public function __toString(): string
    {
        return $this->id ?? '';
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getLists(): ?Lists
    {
        return $this->lists;
    }

    public function setLists(?Lists $lists): static
    {
        $this->lists = $lists;

        return $this;
    }
}
