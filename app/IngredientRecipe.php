<?php

namespace App;

use App\Helpers\Helper;
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
        return Helper::decimal_to_fraction($this->measure_amount);
//        return '<sup>1</sup>&frasl;<sub>2</sub>';
//        return '1&frasl;2';
    }
}
