<div class="grid grid-cols-[2fr_1fr] h-screen">
    <div class="flex flex-col items-center justify-center h-full gap-8">
        <h1 class="self-center text-4xl font-semibold">Masuk</h1>
        <form action="" class="flex flex-col gap-6 w-96">
            <div class="flex flex-col gap-2">
                <label for="username" class="text-lg">Nama Pengguna</label>
                <input type="text" name="username" id="username" placeholder="Masukkan nama pengguna" class="text-lg rounded-sm input input-bordered bg-zinc-50">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-lg">Kata Sandi</label>
                <input type="text" name="password" id="password" placeholder="Masukkan kata sandi" class="text-lg rounded-sm input input-bordered bg-zinc-50">
            </div>
            <div class="flex justify-center text-white">
                <input type="submit" value="Masuk" class="px-5 py-2 text-lg transition-all delay-100 rounded bg-accent hover:opacity-90 hover:cursor-pointer">
            </div>
        </form>
    </div>
    <div class="">
        <img src="{{ asset('images/notebook-2386034_1920.jpg') }}" alt="" class="object-cover object-left h-full">
    </div>
</div>
