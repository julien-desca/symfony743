<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le nom doit faire minimum {{ limit }} caractères",
     *     maxMessage="Le nom doit faire {{ limit }} caractères max."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Le prénom doit faire minimum {{ limit }} caractères",
     *     maxMessage="Le prénom doit faire {{ limit }} caractères max."
     * )
     */
    private $prenom;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
}
