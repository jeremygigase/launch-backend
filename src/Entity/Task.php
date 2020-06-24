<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\TaskRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;


/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ApiFilter(
 *   SearchFilter::class,
 *     properties={
 *         "text": "partial",
 *         "tocomplete" : "exact",
 *          "status" : "exact"
 *     }
 * )
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *         "tocomplete",
 *         "tsk_prj",
 *         "tsk_tag"
 *     },
 *     arguments={"orderParameterName"="_order"}
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={
 *         "tocomplete"
 *     }
 * )
 * @ApiResource(
 *     attributes={"order"={"tocomplete" : "ASC"},
 *         "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true},
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "access_control"="is_granted('ROLE_USER') and object.getTskUsr() == user"
 *         },
 *          "delete"
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={"post"}
 *     }
 * )
 */
class Task implements AuthoredEntityInterface, CreatedDateInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=1)
     * @Groups({"post"})
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post"})
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tsk_usr;

    /**
     * @ORM\Column(type="date")
     * @Groups({"post"})
     * @Assert\NotBlank()
     */
    private $tocomplete;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"post"})
     */
    private $datecompleted;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $weight;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"post"})
     */
    private $public;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     */
    private $tsk_prj;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="tasks")
     */
    private $tsk_tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): CreatedDateInterface
    {
        $this->created = $created;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTskUsr(): ?User
    {
        return $this->tsk_usr;
    }

    public function setUser(?UserInterface $tsk_usr): AuthoredEntityInterface
    {
        $this->tsk_usr = $tsk_usr;

        return $this;
    }
    public function setTskUsr(?User $tsk_usr): self
    {
        $this->tsk_usr = $tsk_usr;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->text;
    }

    public function getTocomplete(): ?\DateTimeInterface
    {
        return $this->tocomplete;
    }

    public function setTocomplete(\DateTimeInterface $tocomplete): self
    {
        $this->tocomplete = $tocomplete;

        return $this;
    }

    public function getDatecompleted(): ?\DateTimeInterface
    {
        return $this->datecompleted;
    }

    public function setDatecompleted(?\DateTimeInterface $datecompleted): self
    {
        $this->datecompleted = $datecompleted;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getTskPrj(): ?Project
    {
        return $this->tsk_prj;
    }

    public function setTskPrj(?Project $tsk_prj): self
    {
        $this->tsk_prj = $tsk_prj;

        return $this;
    }

    public function getTskTag(): ?Tag
    {
        return $this->tsk_tag;
    }

    public function setTskTag(?Tag $tsk_tag): self
    {
        $this->tsk_tag = $tsk_tag;

        return $this;
    }
}
