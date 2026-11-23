<?php

namespace App\Actions\Fortify;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida y registra un usuario nuevo de UniFind.
     *
     * @param array<string, mixed> $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {

            $rol = Rol::where('nombre', 'Estudiante')->first();

            if (!$rol) {
                throw ValidationException::withMessages([
                    'email' => 'No se pudo completar el registro. Contacta al administrador.',
                ]);
            }

            $usuario = new User();

            $usuario->name = trim($input['name']);
            $usuario->email = strtolower(trim($input['email']));
            $usuario->password = $input['password'];
            $usuario->rol_id = $rol->id;

            $usuario->save();

            return $usuario;
        });
    }
}

