<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('public/css/v_search.css') ?>">
    <title>Search Product</title>
</head>

<body>
    <div class="container">
        <iframe id="searchIframe" style="width: 100%; height: 600px; border: none;"></iframe>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script>
    $(document).ready(function() {
        const token = localStorage.getItem('jwt_token');
        if (token) {
            const iframeUrl = `http://10.21.1.125:8000/search?token=${encodeURIComponent(token)}`;
            $('#searchIframe').attr('src', iframeUrl);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Token tidak ditemukan',
                text: 'Silakan login ulang'
            });
        }

        if (token) {
            $.ajax({
                type: 'GET',
                url: 'http://10.21.1.125:8000/api/me',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(Response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'you are in track'
                    });
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Token Error',
                    });
                }
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Sesi anda sudah habis',
                text: ' Silahkan login kembali',
                timer: false,
                showConfirmButton: true,
                confirmButtonColor: '#328E6E',
                confirmButtonText: 'Login'
            }).then((result) => {
                const token = localStorage.getItem('jwt_token');
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('/logout') ?>',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        success: function(response) {
                            localStorage.removeItem('jwt_token');
                            localStorage.removeItem('isLoggedIn');
                            localStorage.removeItem('user_name');
                            Toast.fire({
                                icon: 'success',
                                text: 'Good bye :)',
                            }).then(() => {
                                window.location.href = 'login';
                                localStorage.removeItem('jwt_token');
                                localStorage.removeItem('user_name');
                                window.location.replace('login');
                                window.history.pushState(null, '', 'login');
                                window.addEventListener('popstate', function() {
                                    window.history.pushState(null, '', 'login');
                                });
                            });
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                text: 'Terjadi kesalahan saat logout'
                            });
                        }
                    });
                }
            })
        }

    });
</script>

</html>
<?= $this->endSection() ?>