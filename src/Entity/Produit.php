<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 4,
     *     max = 128
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min = 25,
     *     max = 4000
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=2)
     * @Assert\Type(type="numeric")
     * @Assert\Range(
     *     min=0,
     *     max=999999.99
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="produits")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbViews;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Author;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @ORM\PrePersist
     */
    public function setDefaultValues()
    {
        $this->creationDate = new DateTime();
        $this->setNbViews(0);
    }
    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAtValues()
    {
        $this->setModifiedDate(new DateTime());
    }
    /**
     * Met a jour le slug par rapport au name
     * @return Produit
     */
    public function updateSlug(): self
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->name);
        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modifiedDate;
    }

    public function setModifiedDate(\DateTimeInterface $modifiedDate): self
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    /**
     * @param File $imageFile
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        if (!is_null($imageFile)) {
            $this->modifiedDate = new \DateTimeImmutable();
        }
        $this->imageFile = $imageFile;
    }

    public function getCategories(): ?Category
    {
        return $this->categories;
    }

    public function setCategories(Category $categories): self
    {
        $this->categories = $categories;

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
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getNbViews(): ?int
    {
        return $this->nbViews;
    }

    public function setNbViews(?int $nbViews): self
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }
}
