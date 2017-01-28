<?php
namespace App\Helpers;

class Helper
{
    public static function html_sup($string)
    {
        return preg_replace("/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/", "<sup>$1</sup>", $string);
    }

    public static function textarea_to_array($text)
    {
        return preg_split("/\r\n|\n|\r/", preg_replace("/[\r\n]+/", "\n", $text));
    }

    public static function breadcrumbs($array, $route = '')
    {
        $str = '';
        $url_path = '';

        if (!is_array($array)) {
            $array = explode('/', $array);
        }

        $count_array = count($array);
        $count = 1;

        if ($count_array < 2) return;

        foreach ($array as $key => $val) {
            $tmp = $val;

            if ($count != $count_array) {
                $url_path .= ($count > 1) ? '/'.$key : $key;
                $url = (!empty($route)) ? route($route, $url_path) : $url_path;
                $tmp = '<a href="'.$url.'">'.$val.'</a>';
            }

            $count++;
            $str .= '<li>'.$tmp.'</li>';
        }

        return '<ul class="breadcrumb">'.$str.'</ul>';
    }
}
