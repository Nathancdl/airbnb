<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="logements")
 */
class Logement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $nombreDeChambres = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $nombreDeSallesDeBain = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $prixParNuit = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $adresse = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $ville = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $codePostal = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $pays = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreMaxDePersonnes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="logements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Logement")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNombreDeChambres(): ?int
    {
        return $this->nombreDeChambres;
    }

    public function setNombreDeChambres(int $nombreDeChambres): self
    {
        $this->nombreDeChambres = $nombreDeChambres;

        return $this;
    }

    public function getNombreDeSallesDeBain(): ?int
    {
        return $this->nombreDeSallesDeBain;
    }

    public function setNombreDeSallesDeBain(int $nombreDeSallesDeBain): self
    {
        $this->nombreDeSallesDeBain = $nombreDeSallesDeBain;

        return $this;
    }

    public function getPrixParNuit(): ?float
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(float $prixParNuit): self
    {
        $this->prixParNuit = $prixParNuit;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getNombreMaxDePersonnes(): ?int
    {
        return $this->nombreMaxDePersonnes;
    }

    public function setNombreMaxDePersonnes(int $nombreMaxDePersonnes): self
    {
        $this->nombreMaxDePersonnes = $nombreMaxDePersonnes;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLogement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLogement() === $this) {
                $reservation->setLogement(null);
            }
        }

        return $this;
    }
}