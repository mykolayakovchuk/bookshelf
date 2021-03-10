<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * mainview
 *
 * @ORM\Table(name="mainview")
 * @ORM\Entity
 */
class mainview
{
    /**
     * @var int
     *
     * @ORM\Column(name="idBook", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbook;

    /**
     * @var string
     *
     * @ORM\Column(name="nameBook", type="text", length=65535, nullable=false)
     */
    private $namebook;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Comment", type="text", length=65535, nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imagelink", type="text", length=65535, nullable=true)
     */
    private $imagelink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Authors", type="text", length=65535, nullable=false)
     */
    private $Authors;

    public function getIdbook(): ?int
    {
        return $this->idbook;
    }

    public function getNamebook(): ?string
    {
        return $this->namebook;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getImagelink(): ?string
    {
        return $this->imagelink;
    }

    public function getAuthors(): ?string
    {
        return $this->Authors;
    }


}