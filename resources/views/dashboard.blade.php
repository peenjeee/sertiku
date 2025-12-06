<x-layouts.app title="Verifikasi Sertifikat – SertiKu">

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Ini Halaman Dashboard</p>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold text-gray-700 mb-2">Informasi Akun:</h2>
                <ul class="space-y-1 text-gray-600">
                    <li><span class="font-medium">Nama:</span> {{ auth()->user()->name }}</li>
                    <li><span class="font-medium">Email:</span> {{ auth()->user()->email }}</li>
                </ul>
            </div>
        </div>
    </main>  
</x-layouts.app>

    @if(session('success'))
    <script>
        // Pop-up dengan animasi untuk welcome message
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: '{{ session('
            success ') }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    </script>
    @endif

    <script>
        function confirmLogout() {
            // Pop-up konfirmasi dengan icon warning
            Swal.fire({
                title: 'Logout?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa fa-sign-out"></i> Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Toast loading saat proses logout
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
