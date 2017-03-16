<?php
namespace App\Helpers;

use App\User;
use Auth;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class Helper
{
    public static function is_admin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user->role == 1;
        }

        return false;
    }

    public static function is_owner($user_id)
    {
        if (Auth::check()) {
            if (is_int($user_id)) {
                $user = Auth::user();
                return self::is_admin() || $user->id == $user_id;
            }
        }

        return false;
    }

    public static function is_age_over($age = 21, $birth)
    {
        $age = (int) $age;

        if (!empty($age) && !empty($birth)) {
            if (self::check_my_date($birth)) {
                $birth = Carbon::parse($birth);
                $now = new Carbon('+7 days'); // now + 7 days
                $now->setTime(0, 0, 0);

                if ($birth->diffInYears($now, false) >= $age) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function check_my_date($date)
    {
        $temp = explode('-', $date);

        if (!empty($temp[0]) && !empty($temp[1]) && !empty($temp[2])) {
            return checkdate($temp[1], $temp[2], $temp[0]);
        }

        return false;
    }

    public static function user_valid()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (empty($user->username) || empty($user->birth)) {
                $message = 'Please fill out ';
                $message_mid = '';

                if (empty($user->username)) {
                    $message_mid .= 'username';
                }

                if (empty($user->birth)) {
                    if (!empty($message_mid)) {
                        $message_mid .= ' and ';
                    }

                    $message_mid .= 'date of birth';
                }

                $message .= $message_mid.' before you proceed.';

                return $message;
            }
        }
    }

    public static function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
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

    public static function trim_trailing_zeroes($num)
    {
        return (strpos($num, '.') !== false) ? rtrim(rtrim($num, '0'), '.') : $num;
    }

    public static function nl2empty($string) {
        if (is_array($string)) {
            $string = implode(' ', $string);
        }

        return trim(preg_replace('/\s+/', ' ', $string));
    }

    public static function html_sup($string)
    {
        return preg_replace('/(™|®|©|&trade;|&reg;|&copy;|&#8482;|&#174;|&#169;)/', '<sup>$1</sup>', $string);
    }

    public static function textarea_to_array($string)
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\n|\r/', $string, -1, PREG_SPLIT_NO_EMPTY))));
    }

    public static function nl2p($string, $line_breaks = true, $xml = true)
    {
        if (is_array($string)) {
            $string = implode(PHP_EOL.''.PHP_EOL, $string);
        }

        $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

        if ($line_breaks == true) {
            return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'), trim($string)).'</p>';
        } else {
            return '<p>'.preg_replace(
            array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
            array("</p>\n<p>", "</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'),
            trim($string)).'</p>';
        }
    }

    public static function breadcrumbs($array, $route = '', $home = '')
    {
        $str = '';
        $url_path = '';

        if (!is_array($array)) {
            $array = explode('/', $array);
        }

        $count_array = count($array);
        $count = 1;

        if ($count_array < 1) return;

        if (!empty($home)) {
            $str .= '<li class="breadcrumb-item"><a href="'.route($route).'">'.$home.'</a></li>';
        }

        foreach ($array as $key => $val) {
            $tmp = $val;

            if ($count != $count_array) {
                $url_path .= ($count > 1) ? '/'.$key : $key;
                $url = (!empty($route)) ? route($route, $url_path) : $url_path;
                $tmp = '<a href="'.$url.'">'.$val.'</a>';
            }

            $active = ($count == $count_array) ? ' active' : '';

            $count++;
            $str .= '<li class="breadcrumb-item'.$active.'">'.$tmp.'</li>';
        }

        return '<ol class="breadcrumb">'.$str.'</ol>';
    }

    public static function hashids_random($value = '', $connection = 'main')
    {
        $value = (int) $value;

        if (!empty($value)) {
            $milliseconds = (int) round(microtime(true) * 1000);
            usleep(1000);
            return Hashids::connection($connection)->encode($milliseconds, $value);
        }

        return false;
    }

    public static function get_admin_id()
    {
        $admin_users = User::select('id')->where('role', 1)->get();
        if (!$admin_users->isEmpty()) {
            return array_pluck($admin_users, 'id');
        }

        return false;
    }

    public static function share($title = '', $desc = '', $img = '')
    {
        $title = urlencode($title);
        $desc = urlencode($desc);
        $img = urlencode($img);
        $url = urlencode(url()->full());

        return '
        <div class="social-media">
            <a class="color-link-facebook px-md-2" href="https://www.facebook.com/dialog/share?app_id='.config('services.facebook.client_id').'&display=page&href='.$url.'" target="_blank">
                <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            
            <a class="color-link-twitter px-md-2" href="https://twitter.com/intent/tweet?url='.$url.'&text='.$title.'&via='.config('services.twitter.via').'" target="_blank">
                <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            
            <a class="color-link-pinterest px-md-2" href="https://pinterest.com/pin/create/bookmarklet/?media='.$img.'&url='.$url.'&description='.$title.'" target="_blank">
                <i class="fa fa-pinterest" aria-hidden="true"></i>
            </a>
            
            <a class="color-link-tumblr px-md-2" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl='.$url.'&title='.$title.'&caption='.$desc.'" target="_blank">
                <i class="fa fa-tumblr" aria-hidden="true"></i>
            </a>
            
            <a class="color-link-reddit px-md-2" href="https://reddit.com/submit?url='.$url.'&title='.$title.'" target="_blank">
                <i class="fa fa-reddit" aria-hidden="true"></i>
            </a>
            
            <a class="color-link-googleplus px-md-2" href="https://plus.google.com/share?url='.$url.'" target="_blank">
                <i class="fa fa-google-plus" aria-hidden="true"></i>
            </a>
        </div>';
    }
}
