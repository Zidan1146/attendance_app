@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Data Karyawan</h1>

    <div class="container flex flex-col items-center justify-center">
        <div class="flex justify-center w-full gap-4 my-4">
            <div class="flex w-full">
                <label class="flex items-center w-full gap-2 input input-bordered bg-neutral">
                    <input type="text" name="" id="" placeholder="Search" class="grow" wire:model.live.debounce.1000ms="search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </label>
            </div>
        </div>
        <div class="flex justify-between w-full">
            <div class="flex items-end justify-start w-full gap-2">
                <a href="{{ route('worker.create') }}" wire:navigate class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    Tambah Karyawan Baru
                </a>
            </div>
            <div class="">
                <label>
                    <span class="label">Jabatan</span>
                    <select class="select select-bordered bg-neutral" wire:model.live="selectedRole">
                        <option value="">Semua Jabatan</option>
                        <option value="-1">Tanpa Jabatan</option>
                        @foreach ($roles as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        <div class="flex flex-col w-full gap-6 overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <th></th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Alamat</th>
                    <th>No telepon</th>
                    <th>Jabatan</th>
                    <th>Permission</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    @foreach ($workers as $karyawan)
                        <tr>
                            <td>{{ $loop->iteration + $startNumber }}</td>
                            <td class="flex flex-row gap-2 h-full items-center">
                                @if ($karyawan->foto)
                                    <div class="h-full">
                                        <img src="{{ asset('storage/'.$karyawan->foto) }}" class="w-12 h-12 rounded-md object-cover object-center" alt="{{ $karyawan->nama }}">
                                    </div>
                                @endif
                                <span class="h-full">{{ $karyawan->nama }}</span>
                            </td>
                            <td>{{ $karyawan->username }}</td>
                            <td>{{ $karyawan->alamat }}</td>
                            <td>{{ $karyawan->noTelepon }}</td>
                            <td>{{ $karyawan->jabatan->nama ?? 'Tanpa Jabatan' }}</td>
                            <td>{{ $karyawan->permission }}</td>
                            <td class="flex gap-2">
                                <a class="btn btn-square btn-primary" href="{{ route('worker.edit', ['id' => $karyawan->id]) }}" wire:navigate>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <button class="btn btn-square btn-error" onclick="delete_modal_{{ $loop->index }}.showModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                                        class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                            <td>
                                <dialog id="delete_modal_{{ $loop->index }}" class="modal">
                                    <div class="modal-box">
                                        <h3 class="text-lg font-bold">Apakah anda yakin?</h3>
                                        <p class="py-4">Tindakan ini tidak bisa dibatalkan</p>
                                        <div class="modal-action">
                                            <button class="btn btn-error text-zinc-50" wire:click="delete({{ $karyawan->id }})">Hapus</button>
                                            <form method="dialog">
                                                <button class="btn">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </dialog>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $workers->links() }}
        </div>
    </div>
@endsection
