<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\ResetPasswordAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiFilter(
 *   SearchFilter::class,
 *     properties={
 *         "username": "partial"
 *     }
 * )
 * @ApiResource(
 *      itemOperations={
 *         "get"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             }
 *         },
 *         "put"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *             "denormalization_context"={
 *                 "groups"={"put"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             }
 *         },
 *        "put-reset-password"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *             "method"="PUT",
 *             "path"="/users/{id}/reset-password",
 *             "controller"=ResetPasswordAction::class,
 *             "denormalization_context"={
 *                 "groups"={"put-reset-password"}
 *             }
 *         }
 *     },
 *     collectionOperations={
 *         "get"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *             "normalization_context"={
 *                 "groups"={"get-other-users-info"}
 *             }
 *         },
 *         "post"={
 *             "denormalization_context"={
 *                 "groups"={"post"}
 *             },
 *             "validation_groups"={"post"}
 *     }},
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface, CreatedDateInterface
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const DEFAULT_ROLE = [self::ROLE_USER];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=255, groups={"post", "put"})
     * @Groups({"get", "post", "put", "get-other-users-info"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     * @Groups({"post", "get-owner"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=255)
     * @Groups({"post", "get-owner"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "put", "get-admin", "get-owner"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Email(groups={"post", "put"})
     * @Assert\Length(min=6, max=255, groups={"post", "put"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter",
     * )
     * @Groups({"post"})
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter",
     * )
     * @Groups({"put-reset-password"}))
     */
    private $newPassword;

    /**
     * @Groups({"put-reset-password"}))
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @UserPassword(groups={"put-reset-password"})
     */
    private $oldPassword;


    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="tsk_usr")
     * @Groups({"get", "get-other-users-info"})
     * @ApiSubresource()
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Tag::class, mappedBy="tag_usr")
     * @Groups({"get"})
     * @ApiSubresource()
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="frn_usr1")
     * @Groups({"get-owner"})
     * @ApiSubresource()
     */
    private $friend1;

    /**
     * @ORM\OneToMany(targetEntity=Friend::class, mappedBy="frn_usr2")
     * @Groups({"get-owner"})
     * @ApiSubresource()
     */
    private $friend2;

    /**
     * @ORM\Column(type="date")
     * @Groups({"post", "get"})
     */
    private $created;

    /**
     * @ORM\Column(type="date")
     * @Groups({"post", "get-other-users-info"})
     */
    private $birthday;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"post", "put", "get", "get-other-users-info"})
     */
    private $lastlogin;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="scr_usr")
     * @Groups({"get","get-other-users-info"})
     * @ApiSubresource()
     */
    private $scores;

    /**
     * @ORM\Column(type="simple_array")
     * @Groups({"get-admin", "get-owner"})
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="img_usr")
     * @Groups({"get", "put", "get-other-users-info"})
     * @ApiSubresource()
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="prj_usr")
     * @Groups({"get"})
     * @ApiSubresource()
     */
    private $projects;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $confirmationToken;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->friend1 = new ArrayCollection();
        $this->friend2 = new ArrayCollection();
        $this->scores = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLE;
        $this->enabled = false;
        $this->confirmationToken = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getTskUsr() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setUser($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            // set the owning side to null (unless already changed)
            if ($tag->getTagUsr() === $this) {
                $tag->setUser(null);
            }
        }

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return Collection|Friend[]
     */
    public function getFriend1(): Collection
    {
        return $this->friend1;
    }

    public function addFriend1(Friend $friend1): self
    {
        if (!$this->friend1->contains($friend1)) {
            $this->friend1[] = $friend1;
            $friend1->setUser($this);
        }

        return $this;
    }

    public function removeFriend1(Friend $friend1): self
    {
        if ($this->friend1->contains($friend1)) {
            $this->friend1->removeElement($friend1);
            // set the owning side to null (unless already changed)
            if ($friend1->getFrnUsr1() === $this) {
                $friend1->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friend[]
     */
    public function getFriend2(): Collection
    {
        return $this->friend2;
    }

    public function addFriend2(Friend $friend2): self
    {
        if (!$this->friend2->contains($friend2)) {
            $this->friend2[] = $friend2;
            $friend2->setFrnUsr2($this);
        }

        return $this;
    }

    public function removeFriend2(Friend $friend2): self
    {
        if ($this->friend2->contains($friend2)) {
            $this->friend2->removeElement($friend2);
            // set the owning side to null (unless already changed)
            if ($friend2->getFrnUsr2() === $this) {
                $friend2->setFrnUsr2(null);
            }
        }

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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(\DateTimeInterface $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setUser($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getScrUsr() === $this) {
                $score->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function __toString()
    {
        return (string) $this->username;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getImgUsr() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getPrjUsr() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    public function getPasswordChangeDate()
    {
        return $this->passwordChangeDate;
    }

    public function setPasswordChangeDate($passwordChangeDate): void
    {
        $this->passwordChangeDate = $passwordChangeDate;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }
}
