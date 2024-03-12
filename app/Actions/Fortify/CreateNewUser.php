<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\GoogleRecaptcha;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'g-recaptcha-response'=>['required', new GoogleRecaptcha],
            'email' => [
                'required',
                'string',
                'email',
                'max:300',
                Rule::unique(User::class),
            ],
            'city'=>['required','string','max:250'],
            'phone'=>['required','string','max:20'],
            'accept_term_conditions'=>['accepted'],
            'password' => $this->passwordRules(),
        ],[],[
            'accept_term_conditions'=>'Terminos y condiciones',
            'password'=>'Contraseña',
            'city'=>"Ciudad"
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            // 'address'=> $input['address'],
            'city'=>$input['city'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
