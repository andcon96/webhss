<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('super_user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email',
            'dept' => 'required',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|min:8|max:20|same:password',
        ];
    }

    public function messages()
    {
        return [
            'unique'    => 'Username Must Be Unique',
        ];
    }
}
