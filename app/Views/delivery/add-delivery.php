<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<head>
    <title>Apply Delivery</title>
</head>
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Make Delivery</h3>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <form id="appForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sendName">Nama Pengirim</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" required>
                            </div>
                            <div class="form-group">
                                <label for="sender_address">Alamat Pengirim</label>
                                <input type="text" class="form-control" id="sender_address" name="sender_address" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient_address">Tujuan/Alamat Penerima</label>
                                <input type="text" class="form-control" id="recipient_address" name="recipient_address" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recipient_name">Nama Penerima</label>
                                <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                            </div>
                            <div class="form-group">
                                <label for="sender_phone">No Telp Pengirim</label>
                                <input type="number" class="form-control" id="sender_phone" name="sender_phone" required pattern="\d{10,15}" title="Enter 10 to 15 digits only" inputmode="numeric" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient_phone">No Telp Penerima</label>
                                <input type="number" class="form-control" id="recipient_phone" name="recipient_phone" required pattern="\d{10,15}" title="Enter 10 to 15 digits only" inputmode="numeric" required>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end mt-2 m-2">
            <a href="<?= base_url('/delivery') ?>" class="btn btn-danger m-2">
                <i class="bx bxs-exit"></i>
                Batal</a>
            <button type="submit" class="btn btn-primary m-2">
                <i class="bx bxs-plus-circle"></i>
                Daftar</button>
        </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        const token = localStorage.getItem('jwt_token');

        // Cek token
        if (token) {
            $.ajax({
                type: 'GET',
                url: 'http://10.21.1.125:8000/api/me',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Token Valid'
                    });
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Token Error',
                    });
                }
            });
        }

        // Submit form
        $('#appForm').on('submit', function(e) {
            e.preventDefault();

            const token = localStorage.getItem('jwt_token');
            if (!token) {
                Toast.fire({
                    icon: 'error',
                    title: 'Sesi tidak valid, silakan login ulang'
                });
                return;
            }

            // Inisialisasi FormData
            const formData = new FormData(this);

            $.ajax({
                url: 'http://10.21.1.125:8000/api/delivery',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === true || response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Delivery request saved',
                        }).then(() => {
                            window.location.href = '<?= base_url('/delivery') ?>';
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan, silakan coba lagi'
                        });
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        text: xhr.responseJSON?.message 
                    }).then(() => {
                    //     $.ajax({
                    //         type: 'POST',
                    //         url: '<?= base_url('/logout') ?>',
                    //         headers: {
                    //             'Authorization': 'Bearer ' + token
                    //         },
                    //         success: function(response) {
                    //             localStorage.clear();
                    //             window.location.href = 'login';
                    //         },
                    //         error: function() {
                    //             Toast.fire({
                    //                 icon: 'error',
                    //                 text: 'Gagal logout, coba ulangi'
                    //             });
                    //         }
                    //     });
                    });
                }
            });
        });
    });
</script>



<?= $this->endSection() ?>