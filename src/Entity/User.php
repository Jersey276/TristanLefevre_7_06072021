<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @Hateoas\Relation(
 *      "detail",
 *      href= @Hateoas\Route(
 *          "user_detail",
 *          parameters = { 
 *                  "id" = "expr(object.getId())",
 *              },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          groups="list"
 *      )
 *  )
 * @Hateoas\Relation(
 *      "delete",
 *      href= @Hateoas\Route(
 *          "user_delete",
 *          parameters = { 
 *                  "id" = "expr(object.getId())",
 *              },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(
 *          groups={"list","detail"}
 *      )
 *  )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list","detail"})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detail"})
     */
    private Customer $customer;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"list","detail"})
     * @Assert\NotBlank(
     * message="name cannot be null"
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"detail"})
     * @Assert\NotBlank(
     * message="address cannot be null"
     * )
     */
    private string $address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
