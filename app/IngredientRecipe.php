<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class IngredientRecipe extends Pivot
{
    protected $appends = ['measure_amount_fraction'];


    public function recipe()
    {
        return $this->belongsTo('App\Recipe');
    }

    public function ingredient()
    {
        return $this->belongsTo('App\Ingredient');
    }

    /*public function measures()
    {
        return $this->belongsToMany('App\Measure');
    }*/

    public function getMeasureAmountFractionAttribute()
    {
        if ($this->measure_amount > 0) {
            $value =  $this->measure_amount;
            $whole = floor ( $value );
            $decimal = $value - $whole;
            $leastCommonDenom = 48; // 16 * 3;
            $denominators = array (2, 3, 4, 8, 16, 24, 48 );
            $roundedDecimal = round ( $decimal * $leastCommonDenom ) / $leastCommonDenom;

            if ($roundedDecimal == 0) {
                return $whole;
            }

            if ($roundedDecimal == 1) {
                return $whole + 1;
            }

            foreach ( $denominators as $d ) {
                if ($roundedDecimal * $d == floor ( $roundedDecimal * $d )) {
                    $denom = $d;
                    break;
                }
            }

            return trim(($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom);
        }

        return;
    }
}
