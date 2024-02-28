<?php

use function Livewire\Volt\{state, rules, updated, with, usesPagination, layout};
use App\Models\User;
// use WireUi\Traits\Actions;
// habilita layout volt, usado com rotas volt

layout('components.layout');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'search' => '',
]);

rules([
    'name' => 'required',
    'email' => 'required|email',
    'password' => 'required|min:6',
])->messages([
    'name.required' => 'O Nome é obrigatorio',
    'email.required' => 'O E-mail é obrigatorio',
    'email.email' => 'E-mail em formato invalido',
    'password.required' => 'A senha é obrigatoria',
    'password.min' => 'Senha deve possuir no minimo 6 caracteres',
]);

usesPagination();

with(function () {
    return [
        'users' => User::when($this->search, fn($query) => $query->where('name', 'Ilike', '%' . $this->search . '%'))->paginate(5),
    ];
});

updated([
    'name' => fn() => $this->validateONly('name'),
    'email' => fn() => $this->validateONly('email'),
    'password' => fn() => $this->validateONly('password'),
    'search' => fn() => $this->resetPage(), //zera a pagina ao fazer a pesquisa
]);

$save = function () {
    $this->validate();
    User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
    ]);
    // $this->notification()->success($title = 'Sucesso', $description = 'usuario registrado');
    $this->reset();
    $this->js("alert('Usuario criado')");
};

?>

<div>
    <div class="">
        <form wire:submit.prevent='save' class="flex flex-col  mx-4">
            <label for="">Nome</label>
            <input type="text" wire:model.live.debounce.500ms='name' class="rounded-md">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <label for="">E-mail</label>
            <input type="text" wire:model.live.debounce.500ms='email' class="rounded-md">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <label for="">Senha</label>
            <input type="password" wire:model.live.debounce.500ms='password' class="rounded-md">
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
            <button type="submit" class="rounded-md bg-blue-400 py-2 my-2" wire:loading.attr='disable'
                wire:target='save'>Salvar</button>
        </form>
        <div>
            <span wire:loading wire:target='search'>Loading</span>
        </div>
        <input type="text" class="rounded-md" wire:model.live.debounce.500ms='search' placeholder="Buscar nome...">
        @foreach ($users as $item)
            <p>{{ $item }}</p>
        @endforeach
        {{ $users->links(data: ['scrollTo' => false]) }}
    </div>
</div>
