<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Hateoas\Relation(
 *      "detail",
 *      href= @Hateoas\Route(
 *          "product_detail",
 *          parameters = { "id" = "expr(object.getId())"},
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          groups="list"
 *      )
 *  )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list","detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"list","detail"})

     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"detail"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"list","detail"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Constructor::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail"})
     */
    private $constructor;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getConstructor(): ?Constructor
    {
        return $this->constructor;
    }

    public function setConstructor(?Constructor $constructor): self
    {
        $this->constructor = $constructor;

        return $this;
    }
}
