<?php

namespace Themes\SodaTheme\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
{

    protected $messages = [
        'email.required' => 'Email is required',
        'email.email'    => 'Email should be a valid email address.',
    ];

    protected $rules = [
        'email' => 'required|email',
    ];

    public function rules()
    {
        return $this->rules;
    }

    public function authorize()
    {
        return true;
    }
}

