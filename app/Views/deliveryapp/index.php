<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<head>
    <title>Product Delivery</title>
</head>
<div class="container mt-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Delivery Application</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-stripped" id="tabDeliveryApproval">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nama Pengirim</th>
                            <th>Alamat Pengirim</th>
                            <th>Alamat Penerima</th>
                            <th>Nama Penerima</th>
                            <th>Nomor Telepon Pengirim</th>
                            <th>Nomor Telepon Penerima</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="deliveryTab">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function initTable(token) {
        tabe = $('#tabDeliveryApproval').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: 'http://10.21.1.125:8000/api/delivery',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                },
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'sender_name'
                },
                {
                    data: 'sender_address'
                },
                {
                    data: 'recipient_address'
                },
                {
                    data: 'recipient_name'
                },
                {
                    data: 'sender_phone'
                },
                {
                    data: 'recipient_phone'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let badgeClass = '';
                        if (data === 'pending') {
                            badgeClass = 'badge bg-warning';
                        } else if (data === 'approved') {
                            badgeClass = 'badge bg-success';
                        } else {
                            badgeClass = 'badge bg-secondary';
                        }
                        return `<span class="${badgeClass} text-capitalize">${data}</span>`;
                    }
                }
            ],
            
        });
    }

    $(document).ready(function() {
        const token = localStorage.getItem('jwt_token');
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
                        title: 'You are on track'
                    }).then(() => {
                        initTable(token);
                    })
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
<?= $this->endSection() ?>