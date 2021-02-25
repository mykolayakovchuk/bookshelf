<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Idauthorbook
 *
 * @ORM\Table(name="idauthorbook", indexes={@ORM\Index(name="idBook", columns={"idBook"}), @ORM\Index(name="idAuthor", columns={"idAuthor"})})
 * @ORM\Entity
 */
class Idauthorbook
{
    /**
     * @var int
     *
     * @ORM\Column(name="idid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idid;

    /**
     * @var \Book
     *
     * @ORM\ManyToOne(targetEntity="Book")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idBook", referencedColumnName="idBook")
     * })
     */
    private $idbook;

    /**
     * @var \Author
     *
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAuthor", referencedColumnName="idAuthor")
     * })
     */
    private $idauthor;

    public function getIdid(): ?int
    {
        return $this->idid;
    }

    public function getIdbook(): ?Book
    {
        return $this->idbook;
    }

    public function setIdbook(?Book $idbook): self
    {
        $this->idbook = $idbook;

        return $this;
    }

    public function getIdauthor(): ?Author
    {
        return $this->idauthor;
    }

    public function setIdauthor(?Author $idauthor): self
    {
        $this->idauthor = $idauthor;

        return $this;
    }


}
