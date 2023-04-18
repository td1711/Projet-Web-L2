<?php

namespace Marmi;

class Upload{
    private array $file;

    function __construct($image){
        $this->file = $image;
    }

    function move(string $dir, $title) : string{
        if($this->file['error'] == 0){
            $temp_file_name = $this->file['tmp_name'] ;
            $split = explode(".",$this->file["name"]);
            $extension = end($split);
            $file_name = str_replace(" ","_",$title).".".$extension;
            $dir_name = "..".DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR ;

            if(!is_dir($dir_name))
                mkdir($dir_name) ;

            $full_name = $dir_name . $file_name ;
            move_uploaded_file($temp_file_name, $full_name) ; ?>
            <?php
        }
        return $file_name;
    }

}