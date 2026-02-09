<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.guest')]
class Registro extends Component
{
    public string $name = '';
    public string $cpf = '';
    public string $telefone = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string'],
            'telefone' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function register(): void
    {
        $this->validate();

        // 🔹 Normaliza dados
        $cpf      = preg_replace('/\D/', '', $this->cpf);
        $telefone = preg_replace('/\D/', '', $this->telefone);
        $email    = strtolower(trim($this->email));

        // 🔹 CPF verdadeiro
        if (! $this->cpfValido($cpf)) {
            throw ValidationException::withMessages([
                'cpf' => 'CPF inválido.',
            ]);
        }

        // 🔹 Telefone válido (10 ou 11 dígitos)
        if (! preg_match('/^\d{10,11}$/', $telefone)) {
            throw ValidationException::withMessages([
                'telefone' => 'Telefone inválido.',
            ]);
        }

        // 🔹 CPF já cadastrado
        if (User::where('cpf', $cpf)->exists()) {
            throw ValidationException::withMessages([
                'cpf' => 'Este CPF já está cadastrado.',
            ]);
        }

        // 🔹 E-mail já cadastrado
        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Este e-mail já está cadastrado.',
            ]);
        }

        $user = User::create([
            'name'     => $this->name,
            'cpf'      => $cpf,
            'telefone' => $telefone,
            'email'    => $email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        //dialogo sucesso
        $this->dialog()
            ->success('Sucesso!', 'Cadastrado concluido com sucesso, agora você terá acesso a criar novas<br> solicitacoes de serviço e acesso a todos os protocolos que você seja responsavel!')
            ->flash() 
            ->send();

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Valida CPF verdadeiro
     */
    private function cpfValido(string $cpf): bool
    {
        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }

            $digito = ((10 * $soma) % 11) % 10;
            if ($cpf[$t] != $digito) {
                return false;
            }
        }

        return true;
    }

    protected function messages(): array
    {
        return [
            'password.confirmed' => 'As senhas não conferem.',
        ];
    }


    public function render()
    {
        return view('livewire.guest.registro');
    }
}