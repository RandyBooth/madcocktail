<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

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
                case 'PUT':
                case 'PATCH':
                {
                    return [
                        'title' => 'required',
                        'name' => 'honeypot',
                        'my_time' => 'required|honeytime:2',
                    ];
                }
                default:break;
            }
        }
    }
}
