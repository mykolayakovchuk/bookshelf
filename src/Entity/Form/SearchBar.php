<?php
// class for form, search bar
namespace App\Entity\Form;

class SearchBar
{
    protected $searchString;

    public function getsearchString()
    {
        return $this->searchString;
    }

    public function setsearchString($searchString)
    {
        $this->searchString = $searchString;
    }
}

?>