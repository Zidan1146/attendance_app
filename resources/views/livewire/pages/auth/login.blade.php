<div class="grid grid-cols-[2fr_1fr] h-screen">
    <div class="flex flex-col items-center justify-center h-full gap-8">
        <h1 class="self-center text-4xl font-semibold">Masuk</h1>
        <form wire:submit="login_" class="flex flex-col gap-6 w-96">
            <div class="flex flex-col gap-2">
                <label for="username" class="text-lg">Nama Pengguna</label>
                <input type="text" wire:model.live="username" name="username" id="username" placeholder="Masukkan nama pengguna" class="text-lg rounded-sm input input-bordered bg-zinc-50">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-lg">Kata Sandi</label>
                <input type="password" wire:model.live="password" name="password" id="password" placeholder="Masukkan kata sandi" class="text-lg rounded-sm input input-bordered bg-zinc-50">
            </div>
            @if (session('error'))
                <div class="alert alert-error text-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
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
                    <div wire:loading wire:target="login_">
                        <span class="loading loading-spinner loading-md"></span>
                    </div>

                    Masuk
                </button>
            </div>
        </form>
    </div>
    <div class="">
        <img src="{{ asset('images/notebook-2386034_1920.jpg') }}" alt="" class="object-cover object-left h-full">
    </div>
</div>
