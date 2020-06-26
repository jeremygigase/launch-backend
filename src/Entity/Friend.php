<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiFilter(
 *   SearchFilter::class,
 *     properties={
 *         "request": "exact",
 *         "sender": "exact"
 *     }
 * )
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post",
 *     },
 *     itemOperations={
 *         "get"
 *     }, normalizationContext={"groups"={"friend"}}
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
     * @Groups({"friend"})
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend2")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"friend"})
     */
    private $receiver;

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

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setUser(?UserInterface $sender): AuthoredEntityInterface
    {
        $this->sender = $sender;

        return $this;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function __toString(): string
    {
        return $this->request;
    }
}
