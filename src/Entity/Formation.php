<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité représentant une formation en ligne
 * Jomaa Lilia
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    /**
     * Début de chemin vers les images YouTube
     */
    private const cheminImage = "https://i.ytimg.com/vi/";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual(
        value: 'today',
        message: 'La date ne peut pas être dans le futur.'
    )]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $videoId = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant de la formation
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date de publication
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Définit la date de publication
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    /**
     * Retourne la date de publication formatée en chaîne (dd/mm/yyyy)
     * Retourne une chaîne vide si la date est nulle
     */
    public function getPublishedAtString(): string
    {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    /**
     * Retourne le titre de la formation
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la formation
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Retourne la description de la formation
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la formation
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne l'identifiant de la vidéo YouTube
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Définit l'identifiant de la vidéo YouTube
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;
        return $this;
    }

    /**
     * Retourne l'URL de la miniature de la vidéo YouTube
     */
    public function getMiniature(): ?string
    {
        return self::cheminImage.$this->videoId."/default.jpg";
    }

    /**
     * Retourne l'URL de l'image haute qualité de la vidéo YouTube
     */
    public function getPicture(): ?string
    {
        return self::cheminImage.$this->videoId."/hqdefault.jpg";
    }

    /**
     * Retourne la playlist associée à la formation
     */
    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    /**
     * Définit la playlist associée à la formation
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;
        return $this;
    }

    /**
     * Retourne la liste des catégories de la formation
     *
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Ajoute une catégorie à la formation
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        return $this;
    }

    /**
     * Retire une catégorie de la formation
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);
        return $this;
    }
}
