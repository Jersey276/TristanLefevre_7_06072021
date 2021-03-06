<?php

namespace App\Entity;

use App\Repository\ConstructorRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ConstructorRepository::class)
 */
class Constructor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"detail"})
     */
    private string $name;

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
}
