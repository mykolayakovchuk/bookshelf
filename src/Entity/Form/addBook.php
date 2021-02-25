<?php
// class for form, which add book
namespace App\Entity\Form;

class addBook
{
    protected $nameBook;
    protected $year;
    protected $Comment;
    protected $imagelink;
    protected $bookcover;
    //author name
    protected $idAuthor;

    public function getnameBook()
    {
        return $this->nameBook;
    }

    public function setnameBook($nameBook)
    {
        $this->nameBook = $nameBook;
    }

    public function getyear()
    {
        return $this->year;
    }

    public function setyear($year)
    {
        $this->year = $year;
    }

    public function getComment()
    {
        return $this->Comment;
    }

    public function setComment($Comment)
    {
        $this->Comment = $Comment;
    }

    public function getimagelink()
    {
        return $this->imagelink;
    }

    public function setimagelink($imagelink)
    {
        $this->imagelink = $imagelink;
    }

    public function getidAuthor()
    {
        return $this->idAuthor;
    }

    public function setidAuthor($idAuthor)
    {
        $this->idAuthor = $idAuthor;
    }

    public function getbookcover()
    {
        return $this->bookcover;
    }

    public function setbookcover($bookcover)
    {
        $this->bookcover = $bookcover;
    }

       
}

?>