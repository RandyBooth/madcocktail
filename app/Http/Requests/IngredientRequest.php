<?php

namespace App\Http\Requests;

use App\Ingredient;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class IngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Auth::check()) {
            switch($this->method()) {
                case 'GET':
                case 'DELETE':
                {
                    return [];
                }
                case 'POST':
                {
                    return [
                        'title' => 'required|least_one_letter|min:3|unique:ingredients',
                        'ingredients' => 'present|nullable',
                        'first_name' => 'honeypot',
                        'my_time' => 'required|honeytime:1',
                    ];
                }
                case 'PUT':
                case 'PATCH':
                {
                    $token = $this->route('token');
                    $ingredient = Cache::remember('ingredient_TOKEN_'.$token, 1440, function () use ($token) {
                        return Ingredient::token($token)->first();
                    });

                    return [
                        'title' => ['required', 'least_one_letter', 'min:3', Rule::unique('ingredients')->ignore($ingredient->id)],
                        'first_name' => 'honeypot',
                        'my_time' => 'required|honeytime:1',
                    ];
                }
                default:break;
            }
        }
    }
}
