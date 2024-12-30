<?php

namespace App\Http\Requests;

use App\Rules\GoogleRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'g-recaptcha-response'=>['required', new GoogleRecaptcha],
            'type'=>['required','in:Lost,Found'],
            'animal_kind_id'=>['required','exists:animal_kinds,id'],
            'date'=>['required','date'],
            'name'=>['nullable','string','max:50'],
            'description'=>['required','string'],
            'latitude'=>['required','decimal:3,17'],
            'longitude'=>['required','decimal:3,17'],
            'address'=>['string' ]
        ];
        if(!auth()->user()){
            $rules['email'] = ['required','email'];
        }
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'type' => 'tipo de reporte',
            'animal_kind_id'=>'tipo de animal',
            'date'=>'fecha',
            'name'=>'nombre',
            'description'=>'descripción',
            'latitude'=>'latitud',
            'longitude'=>'longitud',
            'address'=>'direccion',
            'email'=>'correo electrónico'
        ];
    }
}
