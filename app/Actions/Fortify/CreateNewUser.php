<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\ValidEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // cek email itera atau bukan
        $checkEmail = explode("@", $input['email']);
        $nameEmail = $checkEmail[1];
        $validateEmail = ValidEmail::where('name', $nameEmail)->first();
        if (!$validateEmail){
            return response()->json([
                'message' => 'Email Unauthorized',
            ]); 
        }
        $username = $checkEmail[0];

        User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
