<div class="w-screen h-screen">
    <div class="flex flex-col items-center justify-center h-full gap-8">
        <h1 class="self-center text-4xl font-semibold">Daftar</h1>
        <form wire:submit="register" class="flex flex-col items-center justify-center w-full gap-4">
            <div class="flex flex-col gap-2">
                <div class="flex gap-8">
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="nama" class="text-lg">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap"
                            wire:model.live="nama" class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                    </div>
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="username" class="text-lg">Nama Pengguna</label>
                        <input type="text" name="username" id="username" placeholder="Masukkan nama pengguna"
                            wire:model.live="username" class="text-lg rounded-sm bg-zinc-50 input input-bordered">
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
                                wire:model.live="noTelepon" class="w-full text-lg rounded-sm bg-zinc-50 input input-bordered join-item">
                        </div>
                    </div>
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="password" class="text-lg">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi"
                            wire:model.live="password" class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                    </div>
                </div>
                <div class="flex items-center justify-center w-full">
                    <div class="flex flex-col w-full min-w-40 gapy-2">
                        <label for="jabatan" class="text-lg">Jabatan</label>
                        <select wire:model.live="jabatan" name="jabatan" id="jabatan" class="text-lg rounded-sm bg-zinc-50 select select-bordered min-h-6">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full">
                    <div class="flex flex-col w-full min-w-40 gapy-2">
                        <label for="alamat" class="text-lg">Alamat</label>
                        <textarea wire:model.live="alamat" name="alamat" id="alamat" class="text-lg rounded-sm textarea bg-zinc-50" placeholder="Masukkan alamat"></textarea>
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

                        Daftar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
