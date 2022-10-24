<?php

namespace App\Entity;


use App\Entity\Style;
use App\Entity\Artiste;
use App\Entity\Morceau;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 * @UniqueEntity(
 *     fields={"nom","artiste"},
 *          message="il ne peut exister deux albums de méme nom pour un même artiste."
 * )
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de l'album est obligatoire")
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Le nom de l'album doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom de l'album doit faire au maximum {{ limit }} caractères"
     *)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1940,
     *      max = 2099,
     *      notInRangeMessage = "L'année doit être entre {{min}} et {{max}}"
     * )
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Artiste::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="L'artiste ne doit pas être nul")
     */
    private $artiste;

    /**
     * @ORM\OneToMany(targetEntity=Morceau::class, mappedBy="album")
     */
    private $morceaux;

    /**
     * @ORM\ManyToMany(targetEntity=Style::class, mappedBy="albums")
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Vous devez attribuer au moins 1 style"
     * )
     */
    private $styles;

    public function __construct()
    {
        $this->morceaux = new ArrayCollection();
        $this->styles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * @return Collection<int, Morceau>
     */
    public function getMorceaux(): Collection
    {
        return $this->morceaux;
    }

    public function addMorceaux(Morceau $morceaux): self
    {
        if (!$this->morceaux->contains($morceaux)) {
            $this->morceaux[] = $morceaux;
            $morceaux->setAlbum($this);
        }

        return $this;
    }

    public function removeMorceaux(Morceau $morceaux): self
    {
        if ($this->morceaux->removeElement($morceaux)) {
            // set the owning side to null (unless already changed)
            if ($morceaux->getAlbum() === $this) {
                $morceaux->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Style>
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(Style $style): self
    {
        if (!$this->styles->contains($style)) {
            $this->styles[] = $style;
            $style->addAlbum($this);
        }

        return $this;
    }

    public function removeStyle(Style $style): self
    {
        if ($this->styles->removeElement($style)) {
            $style->removeAlbum($this);
        }

        return $this;
    }
}
