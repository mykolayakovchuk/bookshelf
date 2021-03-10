<?php

// class for form, to choose which book you want to edit
namespace App\Entity\Form;

class chooseEditBook
{
    protected $nameBook;

    public function getnameBook()
    {
        return $this->nameBook;
    }

    public function setnameBook($nameBook)
    {
        $this->nameBook = $nameBook;
    }
}
?>