<?php
namespace App\Helpers;

class Helper
{
    public static function is_admin()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            return $user->role == 1;
        }

        return false;
    }

    public static function is_owner($user_id)
    {
        if (\Auth::check()) {
            if (is_int($user_id)) {
                $user = \Auth::user();
                return self::is_admin() || $user->id == $user_id;
            }
        }

        return false;
    }

    public static function decimal_to_fraction($value)
    {
        $value = (float)$value;

        if ($value > 0) {
            $whole = floor($value);
            $decimal = $value - $whole;
            $leastCommonDenom = 48; // 16 * 3;
            $denominators = array(2, 3, 4, 8, 16, 24, 48);
            $roundedDecimal = round($decimal * $leastCommonDenom) / $leastCommonDenom;

            if ($roundedDecimal == 0) {
                return $whole;
            }

            if ($roundedDecimal == 1) {
                return $whole + 1;
            }

            foreach ($denominators as $d) {
                if ($roundedDecimal * $d == floor($roundedDecimal * $d)) {
                    $denom = $d;
                    break;
                }
            }

            return trim(($whole == 0 ? '' : $whole) . ' ' . ($roundedDecimal * $denom) . '/' . $denom);
        }

        return;
    }

    public static function fraction_to_decimal($value)
    {
        $result = 0;
        $value = preg_replace('/[^0-9\. \/]/', '', $value);
        $number = explode(' ', $value);
        $number_count = count($number);

        if (!$number_count == 1 && !$number_count = 2) {
            return $value;
        }

        $fraction = explode('/', $number[$number_count-1]);
        $fraction_count = count($fraction);

        if ($fraction_count == 2) {
            if ($number_count == 2) {
                $result += $number[0];
            }

            $result += $fraction[0]/$fraction[1];
            return round($result, 3);
        }

        return $value;
    }

    public static function fraction_html_encode()
    {

    }

    public static function html_sup($string)
    {
        return preg_replace('/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/', '<sup>$1</sup>', $string);
    }

    public static function textarea_to_array($text)
    {
        return preg_split('/\r\n|\n|\r/', preg_replace('/[\r\n]+/', '\n', $text));
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

    public static function hashids_random($value = '', $connection = 'main')
    {
        $value = (int) $value;

        if (!empty($value)) {
            $milliseconds = (int) round(microtime(true) * 1000);
            usleep(1000);
            return \Hashids::connection($connection)->encode($milliseconds, $value);
        }

        return false;
    }
}
