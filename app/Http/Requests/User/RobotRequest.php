<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RobotRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'signal_id' => 'bail|string',
            'unit_percent' => 'bail|string',
            'limit_percent' => 'bail|string',
            'stop_percent' => 'bail|string'
        ];
    }
}
