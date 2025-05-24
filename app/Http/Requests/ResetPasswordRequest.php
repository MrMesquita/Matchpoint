<?php

namespace App\Http\Requests;

use App\Dtos\ResetPasswordDTO;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function toDTO(): ResetPasswordDTO
    {
        return new ResetPasswordDTO(
            $this->input('email'),
            $this->input('token'),
            $this->input('password'),
            $this->input('password_confirmation')
        );
    }
}
