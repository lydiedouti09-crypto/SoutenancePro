<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
#[UniqueEntity('email', message: 'Un enseignant avec cet email existe déjà.')]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'L\'adresse email n\'est pas valide.')]
    private ?string $email = null;


    #[ORM\Column(length: 255)]
    private ?string $specialite = null;
    #[ORM\Column(length: 255, nullable: true)]
        private ?string $photo = null;

        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        public function setPhoto(?string $photo): static
        {
            $this->photo = $photo;
            return $this;
        }

    /**
     * @var Collection<int, Soutenance>
     */
    #[ORM\OneToMany(targetEntity: Soutenance::class, mappedBy: 'president')]
    private Collection $president;

    /**
     * @var Collection<int, Soutenance>
     */
    #[ORM\OneToMany(targetEntity: Soutenance::class, mappedBy: 'rapporteur')]
    private Collection $rapporteur;

    /**
     * @var Collection<int, Soutenance>
     */
    #[ORM\OneToMany(targetEntity: Soutenance::class, mappedBy: 'examinateur')]
    private Collection $examinateur;

    public function __construct()
    {
        $this->president = new ArrayCollection();
        $this->rapporteur = new ArrayCollection();
        $this->examinateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection<int, Soutenance>
     */
    public function getPresident(): Collection
    {
        return $this->president;
    }

    public function addPresident(Soutenance $president): static
    {
        if (!$this->president->contains($president)) {
            $this->president->add($president);
            $president->setPresident($this);
        }

        return $this;
    }

    public function removePresident(Soutenance $president): static
    {
        if ($this->president->removeElement($president)) {
            // set the owning side to null (unless already changed)
            if ($president->getPresident() === $this) {
                $president->setPresident(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Soutenance>
     */
    public function getRapporteur(): Collection
    {
        return $this->rapporteur;
    }

    public function addRapporteur(Soutenance $rapporteur): static
    {
        if (!$this->rapporteur->contains($rapporteur)) {
            $this->rapporteur->add($rapporteur);
            $rapporteur->setRapporteur($this);
        }

        return $this;
    }

    public function removeRapporteur(Soutenance $rapporteur): static
    {
        if ($this->rapporteur->removeElement($rapporteur)) {
            // set the owning side to null (unless already changed)
            if ($rapporteur->getRapporteur() === $this) {
                $rapporteur->setRapporteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Soutenance>
     */
    public function getExaminateur(): Collection
    {
        return $this->examinateur;
    }

    public function addExaminateur(Soutenance $examinateur): static
    {
        if (!$this->examinateur->contains($examinateur)) {
            $this->examinateur->add($examinateur);
            $examinateur->setExaminateur($this);
        }

        return $this;
    }

    public function removeExaminateur(Soutenance $examinateur): static
    {
        if ($this->examinateur->removeElement($examinateur)) {
            // set the owning side to null (unless already changed)
            if ($examinateur->getExaminateur() === $this) {
                $examinateur->setExaminateur(null);
            }
        }

        return $this;
    }
}
