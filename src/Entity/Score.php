<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiFilter(
 *   SearchFilter::class,
 *     properties={
 *         "date": "exact"
 *     }
 * )
 * @ApiResource(
 *     attributes={"order"={"date" : "DESC"}},
 *     collectionOperations={
 *         "post",
 *     },
 *     itemOperations={
 *         "get",
 *         "put"
 *     }
 * )
 * @ORM\Entity(repositoryClass=ScoreRepository::class)
 */
class Score implements AuthoredEntityInterface , CreatedDateInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"get-friends"})
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $scr_usr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setCreated(\DateTimeInterface $date): CreatedDateInterface
    {
        $this->date = $date;

        return $this;
    }
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getScrUsr(): ?User
    {
        return $this->scr_usr;
    }

    public function setUser(?UserInterface $scr_usr): AuthoredEntityInterface
    {
        $this->scr_usr = $scr_usr;

        return $this;
    }

    public function setScrUsr(?User $scr_usr): self
    {
        $this->scr_usr = $scr_usr;

        return $this;
    }

    public function __toString(): string
    {
        return $this->amount;
    }
}
