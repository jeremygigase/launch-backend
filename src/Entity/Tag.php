<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiFilter(
 *   SearchFilter::class,
 *     properties={
 *         "name": "partial"
 *     }
 * )
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_USER')",
 *     }
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag implements AuthoredEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=1, max="32")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tags")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag_usr;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="tsk_tag")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTagUsr(): ?User
    {
        return $this->tag_usr;
    }

    public function setUser(?UserInterface $tag_usr): AuthoredEntityInterface
    {
        $this->tag_usr = $tag_usr;

        return $this;
    }

    public function setTagUsr(?User $tag_usr): self
    {
        $this->tag_usr = $tag_usr;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setTskTag($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getTskTag() === $this) {
                $task->setTskTag(null);
            }
        }

        return $this;
    }

}
