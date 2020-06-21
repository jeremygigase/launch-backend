<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UploadImageAction;

/**
 * @Vich\Uploadable()
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "method"="POST",
 *             "path"="/images",
 *             "controller"=UploadImageAction::class,
 *             "defaults"={"_api_receive"=false}
 *         }}
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image implements AuthoredEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(nullable=true)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $img_usr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return '/images/' . $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getImgUsr(): ?User
    {
        return $this->img_usr;
    }

    public function setUser(?UserInterface $img_usr): AuthoredEntityInterface
    {
        $this->img_usr = $img_usr;

        return $this;
    }

    public function setImgUsr(?User $img_usr): self
    {
        $this->img_usr = $img_usr;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id . ':' . $this->url;
    }
}
