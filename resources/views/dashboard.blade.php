<x-layouts.app title="Dashboard – SertiKu">

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[24px] border border-[rgba(255,255,255,0.14)] bg-[rgba(15,23,42,0.9)] p-6 shadow-[0_18px_40px_-30px_rgba(15,23,42,1)]">
            <h1 class="text-2xl font-bold text-white mb-4">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>

            <p class="text-[rgba(190,219,255,0.7)]">Ini Halaman Dashboard</p>

            <div class="mt-6 p-4 rounded-[16px] bg-[rgba(15,23,42,0.35)] border border-[rgba(255,255,255,0.05)]">
                <h2 class="font-semibold text-white mb-2">Informasi Akun:</h2>
                <ul class="space-y-1 text-[rgba(190,219,255,0.7)]">
                    <li><span class="font-medium text-white">Nama:</span> {{ auth()->user()->name }}</li>
                    <li><span class="font-medium text-white">Email:</span> {{ auth()->user()->email }}</li>
                </ul>
            </div>
        </div>
    </main>

</x-layouts.app>