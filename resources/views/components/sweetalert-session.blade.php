<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#1e293b', // Slate-800
        color: '#fff',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>

@if(session('success') || request('success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') ?? "Berhasil! Data telah disimpan." }}'
        });
    </script>
@endif

@if(session('password_success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: '{{ session('password_success') }}'
        });
    </script>
@endif

@if(session('info'))
    <script>
        Toast.fire({
            icon: 'info',
            title: '{{ session('info') }}'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#3B82F6',
            background: '#1e293b', // Slate-800
            color: '#fff'
        });
    </script>
@endif

@if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#f59e0b',
            background: '#1e293b', // Slate-800
            color: '#fff'
        });
    </script>
@endif

@if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: '<ul class="text-left text-sm space-y-1 list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonColor: '#EF4444',
            background: '#1e293b', // Slate-800
            color: '#fff'
        });
    </script>
@endif