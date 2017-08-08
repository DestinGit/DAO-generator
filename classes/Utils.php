<?php

class Utils
{

    public static function camelize($str)
    {
        $pattern = "#\_[a-z]#";
        return preg_replace_callback($pattern, function($matches){
            $matches = array_map(function($item){
                return strtoupper(substr($item,1,1));
            }, $matches);
            return implode('',$matches);
        },$str);

    }

    public static function underscorise($str){
        $pattern = "#[A-Z]#";
        return (preg_replace_callback($pattern, function($matches){
            $matches = array_map(function($item){
                return "_".strtolower($item);
            }, $matches);
            return implode('',$matches);
        },$str));
    }

}