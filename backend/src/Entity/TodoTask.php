<?php

namespace App\Entity;

use App\Repository\TodoTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoTaskRepository::class)
 */
class TodoTask
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $TaskSubject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $TaskDetail;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $UpdatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskSubject(): ?string
    {
        return $this->TaskSubject;
    }

    public function setTaskSubject(string $TaskSubject): self
    {
        $this->TaskSubject = $TaskSubject;

        return $this;
    }

    public function getTaskDetail(): ?string
    {
        return $this->TaskDetail;
    }

    public function setTaskDetail(string $TaskDetail): self
    {
        $this->TaskDetail = $TaskDetail;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }
}
