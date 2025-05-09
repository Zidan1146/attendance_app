<form wire:submit="login_" class="flex flex-col gap-6 w-96">
    @csrf
    <div class="flex flex-col gap-2">
        <label for="username" class="text-lg">Nama Pengguna</label>
        <input type="text" wire:model.live="username"
        name="username" id="username"
        placeholder="Masukkan nama pengguna"
        class="text-lg rounded-sm input input-bordered bg-zinc-50">
    </div>
    <div class="flex flex-col gap-2">
        <label for="password" class="text-lg">Kata Sandi</label>
        <input type="password" wire:model.live="password"
        name="password"
        id="password"
        placeholder="Masukkan kata sandi"
        class="text-lg rounded-sm input input-bordered bg-zinc-50">
    </div>
    @if (session('error'))
        <x-alert.form-error-alert>
            <x-alert.form-error-alert-item>
                {{ session('error') }}
            </x-alert.form-error-alert-item>
        </x-alert.form-error-alert>
    @endif
    @if ($errors->any())
        <x-alert.form-error-alert>
            @foreach ($errors->all() as $error)
                <x-alert.form-error-alert-item>
                    {{ $error }}
                </x-alert.form-error-alert-item>
            @endforeach
        </x-alert.form-error-alert>
    @endif
    <div class="flex justify-center text-white">
        <button type="submit" class="btn btn-primary">
            <div wire:loading wire:target="login_">
                <span class="loading loading-spinner loading-md"></span>
            </div>

            Masuk
        </button>
    </div>
</form>
