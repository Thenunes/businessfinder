<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessRepository")
 */
class Business
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="businesses")
     * @ORM\JoinColumn(nullable=false, name="id_city")
     */
    private $id_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(
     * name="business_category",
     * joinColumns={@ORM\JoinColumn(name="id_business", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="id_category", referencedColumnName="id")}
     * )
     */
    private $categories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCity(): ?City
    {
        return $this->id_city;
    }

    public function setIdCity(?City $id_city): self
    {
        $this->id_city = $id_city;

        return $this;
    }

    public function getIdState(): ?State
    {

        return !empty($this->id_city) ? $this->id_city->id_state : null;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

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

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    public function getListCategories(){

        $categories = $this->categories->getValues();
        $categories = array_map(function($category){
            return $category->getName();
        }, $categories);
        
        return implode(', ', $categories);
    }
}
