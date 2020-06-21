<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post",
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass=FriendRepository::class)
 */
class Friend implements AuthoredEntityInterface
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
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $frn_usr1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend2")
     * @ORM\JoinColumn(nullable=false)
     */
    private $frn_usr2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getFrnUsr1(): ?User
    {
        return $this->frn_usr1;
    }

    public function setUser(?UserInterface $frn_usr1): AuthoredEntityInterface
    {
        $this->frn_usr1 = $frn_usr1;

        return $this;
    }

    public function setFrnUsr1(?User $frn_usr1): self
    {
        $this->frn_usr1 = $frn_usr1;

        return $this;
    }

    public function getFrnUsr2(): ?User
    {
        return $this->frn_usr2;
    }

    public function setFrnUsr2(?User $frn_usr2): self
    {
        $this->frn_usr2 = $frn_usr2;

        return $this;
    }

    public function __toString(): string
    {
        return $this->request;
    }
}
