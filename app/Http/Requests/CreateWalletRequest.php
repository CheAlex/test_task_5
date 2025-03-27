<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * @property string $currency
 * @property string $address
 */
class CreateWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => 'required|max:15|in:BTC,LTC,ETH',
            'address' => [
                'required',
                'max:250',
                Rule::unique('wallets', 'address')->where('currency', $this->input('currency')),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'address.unique' => 'The address has already been taken for this currency.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
