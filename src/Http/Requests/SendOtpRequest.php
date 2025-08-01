<?php
namespace OtpLogin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string|min:10|max:15',
            'country_code' => 'required|string|max:5',
        ];
    }

    public function fullPhone(): string
    {
        return $this->country_code . $this->phone;
    }
}
