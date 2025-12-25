<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>

@if(session('success'))
    <script>
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
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
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@if(session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#f59e0b'
        });
    </script>
@endif