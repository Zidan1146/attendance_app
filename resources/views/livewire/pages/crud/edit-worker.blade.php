@extends('livewire.layout.app')

@section('content')
    <div class="w-full h-full">
        <div class="flex items-center gap-x-3">
            <a href="{{ route('worker') }}" wire:navigate class="h-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-2xl">Data Karyawan</h1>
        </div>
        <div class="flex flex-col items-center justify-center h-full gap-8">
            <h1 class="self-center text-4xl font-semibold">Edit <span>{{ $form->nama }}</span></h1>
            <form wire:submit="update" class="flex flex-col items-center justify-center w-full gap-4">
                <div class="flex flex-col gap-2">
                    <div class="flex gap-8">
                        <div class="flex flex-col min-w-40 w-80 gapy-2">
                            <label for="nama" class="text-lg">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap"
                                wire:model.live="form.nama" class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                        </div>
                        <div class="flex flex-col min-w-40 w-80 gapy-2">
                            <label for="username" class="text-lg">Nama Pengguna</label>
                            <input type="text" name="username" id="username" placeholder="Masukkan nama pengguna"
                                wire:model.live="form.username" class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-full gap-8">
                        <div class="flex flex-col min-w-40 w-80 gapy-2">
                            <label for="noTelepon" class="text-lg">No Telepon</label>
                            <div class="w-full join">
                                <div class="px-1 border border-primary join-item">
                                    <span class="flex items-center h-full">+62</span>
                                </div>
                                <input type="tel" name="noTelepon" id="noTelepon" placeholder="Masukkan no telepon"
                                    wire:model.live="form.noTelepon"
                                    class="w-full text-lg rounded-sm bg-zinc-50 input input-bordered join-item">
                            </div>
                        </div>
                        <div class="flex flex-col min-w-40 w-80 gapy-2">
                            <label for="password" class="text-lg">Kata Sandi (Opsional)</label>
                            <input type="password"
                                wire:model.live="form.password"
                                class="text-lg rounded-sm bg-zinc-50 input input-bordered"
                                placeholder="Masukkan Kata Sandi Baru">
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-full">
                        <div class="flex flex-col w-full min-w-40 gapy-2">
                            <label for="jabatan" class="text-lg">Jabatan</label>
                            <select wire:model.live="form.jabatan" name="jabatan" id="jabatan"
                                class="text-lg rounded-sm bg-zinc-50 select select-bordered min-h-6">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-full">
                        <div class="flex flex-col w-full min-w-40 gapy-2">
                            <label for="alamat" class="text-lg">Alamat</label>
                            <textarea wire:model.live="form.alamat" name="alamat" id="alamat"
                                class="text-lg rounded-sm textarea bg-zinc-50" placeholder="Masukkan alamat"></textarea>
                        </div>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-error">
                            <div class="flex flex-col justify-start">
                                <span class="text-lg text-zinc-50">Form Invalid:</span>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-zinc-50">- {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-center text-white">
                        <button type="submit" class="btn btn-primary">
                            <div wire:loading wire:target="register">
                                <span class="loading loading-spinner loading-md"></span>
                            </div>

                            Edit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
