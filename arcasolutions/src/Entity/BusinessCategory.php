<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessCategoryRepository")
 */
class BusinessCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="businessCategories")
     * @ORM\JoinColumn(nullable=false, name="id_business")
     */
    private $id_business;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="businessCategories")
     * @ORM\JoinColumn(nullable=false, name="id_category")
     */
    private $id_category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdBusiness(): ?Business
    {
        return $this->id_business;
    }

    public function setIdBusiness(?Business $id_business): self
    {
        $this->id_business = $id_business;

        return $this;
    }

    public function getIdCategory(): ?Category
    {
        return $this->id_category;
    }

    public function setIdCategory(?Category $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }
}
