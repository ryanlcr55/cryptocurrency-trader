<?php

namespace App\Http\Requests\Signal;

use Illuminate\Foundation\Http\FormRequest;

class ReceivedSignalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|require|string',
            'coin' => 'bail|require|string',
            'action' => 'bail|require|string|in:buy,sell',
        ];
    }
}
