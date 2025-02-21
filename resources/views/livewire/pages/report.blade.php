@extends('livewire.layout.app')

@section('content')
    <h1 class="text-2xl">Rekap Presensi</h1>

    <div class="flex flex-col justify-center gap-5">
        <div class="flex justify-start w-2/3">
            <form action="" class="gap-3 form-control">
                <div class="flex w-full gap-2">
                    <label class="w-48">
                        <div class="label">
                            <span class="label-text">Pegawai</span>
                        </div>
                        <select name="" id="" class="w-full select bg-neutral select-bordered">
                            <option value="">Semua</option>
                        </select>
                    </label>
                    <label class="w-48">
                        <div class="label">
                            <span class="label-text">Jabatan</span>
                        </div>
                        <select name="" id="" class="w-full select bg-neutral select-bordered">
                            <option value="">Semua</option>
                        </select>
                    </label>
                </div>
                <div class="flex gap-2">
                    <label class="w-48">
                        <div class="label">
                            <span class="label-text">Periode Bulan</span>
                        </div>
                        <select name="" id="" class="w-full select bg-neutral select-bordered">
                            <option value="">Januari</option>
                        </select>
                    </label>
                    <label class="w-48">
                        <div class="label">
                            <span class="label-text">Periode Tahun</span>
                        </div>
                        <select name="" id="" class="w-full select bg-neutral select-bordered">
                            <option value="">2025</option>
                        </select>
                    </label>
                </div>
                <input type="submit" value="Simpan" class="w-1/3 btn btn-primary" name="" id="">
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full border border-gray-300 table-zebra">
                <thead>
                    <tr class="text-gray-700 bg-gray-200">
                        <th rowspan="2" class="border border-gray-300">No</th>
                        <th rowspan="2" class="border border-gray-300">Nama</th>
                        <th colspan="31" class="border border-gray-300">Januari 2025</th>
                    </tr>
                    <tr class="text-gray-600 bg-gray-100">
                        <th class="p-1 text-center border border-gray-300">1</th>
                        <th class="p-1 text-center border border-gray-300">2</th>
                        <th class="p-1 text-center border border-gray-300">3</th>
                        <th class="p-1 text-center border border-gray-300">4</th>
                        <th class="p-1 text-center border border-gray-300">5</th>
                        <th class="p-1 text-center border border-gray-300">6</th>
                        <th class="p-1 text-center border border-gray-300">7</th>
                        <th class="p-1 text-center border border-gray-300">8</th>
                        <th class="p-1 text-center border border-gray-300">9</th>
                        <th class="p-1 text-center border border-gray-300">10</th>
                        <th class="p-1 text-center border border-gray-300">11</th>
                        <th class="p-1 text-center border border-gray-300">12</th>
                        <th class="p-1 text-center border border-gray-300">13</th>
                        <th class="p-1 text-center border border-gray-300">14</th>
                        <th class="p-1 text-center border border-gray-300">15</th>
                        <th class="p-1 text-center border border-gray-300">16</th>
                        <th class="p-1 text-center border border-gray-300">17</th>
                        <th class="p-1 text-center border border-gray-300">18</th>
                        <th class="p-1 text-center border border-gray-300">19</th>
                        <th class="p-1 text-center border border-gray-300">20</th>
                        <th class="p-1 text-center border border-gray-300">21</th>
                        <th class="p-1 text-center border border-gray-300">22</th>
                        <th class="p-1 text-center border border-gray-300">23</th>
                        <th class="p-1 text-center border border-gray-300">24</th>
                        <th class="p-1 text-center border border-gray-300">25</th>
                        <th class="p-1 text-center border border-gray-300">26</th>
                        <th class="p-1 text-center border border-gray-300">27</th>
                        <th class="p-1 text-center border border-gray-300">28</th>
                        <th class="p-1 text-center border border-gray-300">29</th>
                        <th class="p-1 text-center border border-gray-300">30</th>
                        <th class="p-1 text-center border border-gray-300">31</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border border-gray-300">1</td>
                        <td class="p-2 border border-gray-300">Zidan</td>
                        <td class="p-0 text-center text-white bg-green-500 border border-gray-300">v</td>
                        <td class="p-0 text-center text-black bg-pink-300 border border-gray-300">tl</td>
                        <td class="p-0 text-center text-black bg-blue-300 border border-gray-300">pw</td>
                        <td class="p-0 text-center text-white bg-green-500 border border-gray-300">v</td>
                        <td class="p-0 text-center text-black bg-pink-300 border border-gray-300">tp</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="join">
            <button class="join-item btn btn-primary">1</button>
            <button class="join-item btn btn-primary btn-active">2</button>
            <button class="join-item btn btn-primary">3</button>
            <button class="join-item btn btn-primary">4</button>
        </div>
    </div>
@endsection
