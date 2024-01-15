<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'=>['required','exists:reports,id'],
            'type'=>['required','in:Lost,Found'],
            'animal_kind_id'=>['required','exists:animal_kinds,id'],
            'date'=>['required','date'],
            'name'=>['required','string','max:50'],
            'description'=>['required','string'],
            'latitude'=>['required','decimal:3,17'],
            'longitude'=>['required','decimal:3,17'],
            'address'=>['string']
        ];
    }
}
