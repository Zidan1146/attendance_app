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
                    <div class="flex items-center justify-center w-full gap-8">
                        <div class="flex flex-col w-full min-w-40 gapy-2">
                            <label for="jabatan" class="text-lg">Jabatan</label>
                            <select wire:model.live="form.jabatan_id" name="jabatan" id="jabatan"
                                class="text-lg rounded-sm bg-zinc-50 select select-bordered min-h-6">
                                @foreach ($roles as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full min-w-40 gapy-2 {{ !$isSuperAdmin ? 'tooltip tooltip-bottom' : '' }}"
                            data-tip="Hanya Super Admin yang dapat edit permission">
                            <label for="permission" class="text-lg text-left">Permission</label>
                            <select wire:model.live="form.permission" name="permission" id="permission"
                                class="text-lg rounded-sm bg-zinc-50 select select-bordered min-h-6"
                                @disabled(!$isSuperAdmin)>
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->value }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-full gap-8">
                        <div class="flex flex-col w-full min-w-40 gapy-2">
                            <div class="flex items-center justify-center w-full gap-8">
                                <div class="flex flex-col w-full min-w-40 gapy-2">
                                    <fieldset class="fieldset w-full">
                                        <legend class="fieldset-legend">Foto (Opsional)</legend>
                                        <input type="file" class="file-input w-full" wire:model.live="form.foto" accept="image/*" />
                                        <label class="label">Ukuran Maksimal 2MB</label>
                                    </fieldset>
                                    <div class="flex flex-row gap-4">
                                        @if ($existingAvatar)
                                            <div
                                                class="tooltip"
                                                data-tip="Foto sebelumnya">
                                                <img
                                                    src="{{ asset('storage/'.$existingAvatar) }}"
                                                    class="w-12 h-12 rounded-md object-cover object-center"
                                                    alt="Foto Sebelumnya">
                                            </div>
                                        @endif
                                        @if($form->foto)
                                            <div
                                                class="tooltip"
                                                data-tip="Foto terupload">
                                                <img
                                                    src="{{ $form->foto->temporaryUrl() }}"
                                                    class="w-12 h-12 rounded-md object-cover object-center"
                                                    alt="{{ $form->foto }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
