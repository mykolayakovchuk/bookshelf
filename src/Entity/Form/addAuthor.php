<?php
// class for form, which add author to DB
namespace App\Entity\Form;

class addAuthor
{
    protected $nameAuthor;
    protected $surnameAuthor;
    protected $Comment;

    public function getnameAuthor()
    {
        return $this->nameAuthor;
    }

    public function setnameAuthor($nameAuthor)
    {
        $this->nameAuthor = $nameAuthor;
    }

    public function getsurnameAuthor()
    {
        return $this->surnameAuthor;
    }

    public function setsurnameAuthor($surnameAuthor)
    {
        $this->surnameAuthor = $surnameAuthor;
    }

    public function getComment()
    {
        return $this->Comment;
    }

    public function setComment($Comment)
    {
        $this->Comment = $Comment;
    }
    
}

?>