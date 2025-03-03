<?php

namespace App\Entity;

use App\Repository\InfosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfosRepository::class)]
class Infos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, name: "`rank`")]
    private string $rank;

    #[ORM\Column(type: 'string', length: 255)]
    private string $victory;

    #[ORM\Column(type: 'string', length: 255)]
    private string $defeat;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'infos')]
    private ?User $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }



    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank(string $rank): void
    {
        $this->rank = $rank;
    }

    public function getVictory(): string
    {
        return $this->victory;
    }

    public function setVictory(string $victory): void
    {
        $this->victory = $victory;
    }

    public function getDefeat(): string
    {
        return $this->defeat;
    }

    public function setDefeat(string $defeat): void
    {
        $this->defeat = $defeat;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
}
