<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AuthoredEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *     }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project implements AuthoredEntityInterface
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prj_usr;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="tsk_prj")
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

    public function getPrjUsr(): ?User
    {
        return $this->prj_usr;
    }

    public function setUser(?UserInterface $prj_usr): AuthoredEntityInterface
    {
        $this->prj_usr = $prj_usr;

        return $this;
    }

    public function setPrjUsr(?User $prj_usr): self
    {
        $this->prj_usr = $prj_usr;

        return $this;
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
            $task->setTskPrj($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getTskPrj() === $this) {
                $task->setTskPrj(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
