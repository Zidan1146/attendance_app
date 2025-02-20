<div class="w-screen h-screen">
    <div class="flex flex-col items-center justify-center h-full gap-8">
        <h1 class="self-center text-4xl font-semibold">Daftar</h1>
        <form action="" class="flex flex-col items-center justify-center w-full gap-4">
            <div class="flex flex-col gap-2">
                <div class="flex gap-8">
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="name" class="text-lg">Nama Lengkap</label>
                        <input type="text" name="name" id="name" placeholder="Masukkan nama lengkap"
                            class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                    </div>
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="username" class="text-lg">Nama Pengguna</label>
                        <input type="text" name="username" id="username" placeholder="Masukkan nama pengguna"
                            class="text-lg rounded-sm bg-zinc-50 input input-bordered" {{ $isHr ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full gap-8">
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="telNumber" class="text-lg">No Telepon</label>
                        <input type="tel" name="telNumber" id="telNumber" placeholder="Masukkan no telepon"
                            class="text-lg rounded-sm bg-zinc-50 input input-bordered">
                    </div>
                    <div class="flex flex-col min-w-40 w-80 gapy-2">
                        <label for="password" class="text-lg">Kata Sandi</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi"
                            class="text-lg rounded-sm bg-zinc-50 input input-bordered" {{ $isHr ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full">
                    <div class="flex flex-col w-full min-w-40 gapy-2">
                        <p>Selected: {{ $selectedRole }}</p>
                        <p>IsHR: {{ $isHr }}</p>
                        <label for="position" class="text-lg">Jabatan</label>
                        <select wire:model.live="selectedRole" name="position" id="position" class="text-lg rounded-sm bg-zinc-50 input input-bordered min-h-6">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-center w-full">
                    <div class="flex flex-col w-full min-w-40 gapy-2">
                        <label for="address" class="text-lg">Alamat</label>
                        <textarea name="address" id="address" class="text-lg rounded-sm textarea bg-zinc-50" placeholder="Masukkan alamat"></textarea>
                    </div>
                </div>
                <div class="flex justify-center text-white">
                    <input type="submit" value="Masuk"
                        class="px-5 py-2 text-lg transition-all delay-100 rounded bg-accent hover:opacity-90 hover:cursor-pointer">
                </div>
            </div>
        </form>
    </div>
</div>
