
<div class="relative flex">
    <aside class="fixed h-screen p-4 text-white transition-all duration-150 bg-gray-900"
        style="width: {{ $isCollapsed ? '5rem' : '16rem' }}">
        <!-- Menu Button -->
        <div class="flex flex-row justify-center w-full">
            <button wire:click="toggleCollapse"
                class="flex items-center p-2 mb-4 text-white rounded focus:outline-none hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                        d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            <h1
                class="flex items-center justify-center mb-4 text-lg font-bold text-center w-full {{ $isCollapsed ? 'hidden' : 'block' }}">
                ABSENSI</h1>
        </div>

        <ul>
            <li class="flex items-center p-2 mb-2 rounded cursor-pointer {{ ($routeName ?: Route::currentRouteName()) === 'dashboard' ? ' bg-gray-700 ' : '' }}hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                    <path fill-rule="evenodd"
                        d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="{{ $isCollapsed ? 'hidden' : 'inline' }}">Dashboard</span>
            </li>
            <li class="flex items-center p-2 mb-2 rounded cursor-pointer {{ ($routeName ?: Route::currentRouteName()) === 'absensi' ? ' bg-gray-700 ' : '' }} hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                    <path fill-rule="evenodd"
                        d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="{{ $isCollapsed ? 'hidden' : 'inline' }}">Data Absensi</span>
            </li>
            <li class="flex items-center p-2 mb-2 rounded cursor-pointer {{ ($routeName ?: Route::currentRouteName()) === 'karyawan' ? ' bg-gray-700 ' : '' }} hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                    <path fill-rule="evenodd"
                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="{{ $isCollapsed ? 'hidden' : 'inline' }}">Data Karyawan</span>
            </li>
            <li class="flex items-center p-2 mb-2 rounded cursor-pointer {{ ($routeName ?: Route::currentRouteName()) === 'laporan' ? ' bg-gray-700 ' : '' }} hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                    <path
                        d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                    <path
                        d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                </svg>
                <span class="{{ $isCollapsed ? 'hidden' : 'inline' }}">Laporan</span>
            </li>
        </ul>

        <div class="absolute bottom-4 left-4 right-4">
            <button class="flex items-center w-full p-2 text-white rounded hover:bg-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-1 size-6">
                    <path fill-rule="evenodd"
                        d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
                <span class="{{ $isCollapsed ? 'hidden' : 'inline' }}">Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-4 transition-all duration-300" style="margin-left: {{ $isCollapsed ? '5rem' : '16rem' }}">
        @yield('content')
    </div>
</div>
