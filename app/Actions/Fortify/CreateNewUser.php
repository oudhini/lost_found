<?php

    namespace App\Actions\Fortify;

    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Auth; // Import de Auth
    use Illuminate\Support\Facades\Session; // Import de Session
    use Illuminate\Validation\Rule;
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
        // dd($input);
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    // 'unique:users',
                    'max:255',
                    Rule::unique(User::class),
                ],
                'phone' => ['required', 'string', 'max:15', Rule::unique(User::class)],
                'password' => $this->passwordRules()
            ],[
                'name.required' => 'Le nom est obligatoire.',
                 'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
                'phone.required' => 'Le numéro de téléphone est obligatoire.',
                'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            ])->validate();
                
            // if (Validator::fails()) {
            //     return response()->json(["errors" => $validator->errors()], 422);
            // }

            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password' => Hash::make($input['password']),
            ]);
           
        }
    }
?>
