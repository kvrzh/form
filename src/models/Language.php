<?php

namespace app\models;


class Language
{
    private $language = null;

    public function __construct($language)
    {
        $this->language = $language;
    }

    public function getText($json = false){
        $data = file_get_contents('public/lang/'.$this->language.'.json');
        if($json === true){
            return $data;
        }
        return json_decode($data);
    }
}