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
            <div class="d-flex gap-2 justify-content-end mb-3">
                <button class="btn btn-warning" id="filterBtn">
                    <i class="bx bx-filter"></i>
                    Filter Delivery
                </button>
                <a href="<?= base_url('/add') ?>" class="btn btn-success" id="addBtn">
                    <i class="bx bx-plus-circle"></i>Apply Delivery
                </a>
            </div>
            <div class="table-responsive">
                <div class="container mt-4 mb-4" id="filterTab" style="display: none;">
                    <div class="body mb-3">
                        <div class="container">
                            <form id="filter" class="d-flex align-items-end gap-3 flex-wrap">
                                <div class="form-group">
                                    <label for="from_date">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="from_date" id="from_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="to_date">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="to_date" id="to_date" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="submitFilter">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-stripped" id="tabDelivery">
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
    $('#filterBtn').on('click', function() {
        $('#filterTab').slideToggle();
    });

    let table;

    function initTable(token) {
        table = $('#tabDelivery').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: 'http://10.21.1.125:8000/api/delivery',
                method: 'GET',
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
            ]
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

        $('#filter').on('submit', function(e) {
            e.preventDefault();
            if (table) {
                table.ajax.reload(); // reload datatable berdasarkan input filter
            }
        });
    });
</script>
<?= $this->endSection() ?>