<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity
 */
class Author
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAuthor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idauthor;

    /**
     * @var string
     *
     * @ORM\Column(name="nameAuthor", type="string", length=50, nullable=false)
     */
    private $nameauthor;

    /**
     * @var string
     *
     * @ORM\Column(name="surnameAuthor", type="string", length=50, nullable=false)
     */
    private $surnameauthor;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Comment", type="text", length=65535, nullable=true)
     */
    private $comment;

    public function getIdauthor(): ?int
    {
        return $this->idauthor;
    }

    public function getNameauthor(): ?string
    {
        return $this->nameauthor;
    }

    public function setNameauthor(string $nameauthor): self
    {
        $this->nameauthor = $nameauthor;

        return $this;
    }

    public function getSurnameauthor(): ?string
    {
        return $this->surnameauthor;
    }

    public function setSurnameauthor(string $surnameauthor): self
    {
        $this->surnameauthor = $surnameauthor;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }


}
