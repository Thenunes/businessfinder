<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BusinessCategory", mappedBy="id_category")
     */
    private $businessCategories;

    public function __construct()
    {
        $this->businessCategories = new ArrayCollection();
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

    /**
     * @return Collection|BusinessCategory[]
     */
    public function getBusinessCategories(): Collection
    {
        return $this->businessCategories;
    }

    public function addBusinessCategory(BusinessCategory $businessCategory): self
    {
        if (!$this->businessCategories->contains($businessCategory)) {
            $this->businessCategories[] = $businessCategory;
            $businessCategory->setIdCategory($this);
        }

        return $this;
    }

    public function removeBusinessCategory(BusinessCategory $businessCategory): self
    {
        if ($this->businessCategories->contains($businessCategory)) {
            $this->businessCategories->removeElement($businessCategory);
            // set the owning side to null (unless already changed)
            if ($businessCategory->getIdCategory() === $this) {
                $businessCategory->setIdCategory(null);
            }
        }

        return $this;
    }
}
