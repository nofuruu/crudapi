<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<head>
    <title>Product Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<style>
    .container-table {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
    }

    .table-header {
        margin: 0;
        font-size: 1.25rem;
        color: #333;
    }

    .table-body {
        padding: 20px;
    }

    .table-responsive {
        margin: 0 20px;
    }

    #add-tab {
        margin: 20px;
        border: 1px solid #dee2e6;
    }

    .form-control {
        border-radius: 4px;
    }
</style>

<div class="container-table mt-4 mb-4">
    <div class="card ">
        <div class="card-header">
            <h3 class="table-header">Table produk</h3>
        </div>
        <div class="table-body">
            <div class="d-flex gap-2 justify-content-end mt-3 mb-3 m-3">
                <button class="btn btn-success" id="addBtn">
                    <i class='bx bx-plus-circle'></i>
                    Add Data
                </button>
                <form role="search">
                    <input class="form-control" type="search" id="searchInput" placeholder="Search" aria-label="Search" style="width: 200px;">
                </form>
            </div>
            <div class="table-responsive">
                <div class="card mt-4 mb-4" id="add-tab" style="display:none;">
                    <div class="card-body">
                        <div class="row col-md-12">
                            <div class="form-group">
                                <form id="formProduk">
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" Required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-stripped" id="tabProduct">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="prodTab">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const token = localStorage.getItem('jwt_token');
    const apiUrl = 'http://10.21.1.125:8000/api/produk';

    $.ajaxSetup({
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });

    function editPage(id) {
        const token = localStorage.getItem('jwt_token');
        if (token) {
            localStorage.setItem('edit_produk_id', id);
            window.location.href = "<?= base_url('edit') ?>"
        } else {
            Toast.fire({
                icon: 'error',
                title: 'Session expired, Please Login'
            }).then(() => {
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
                        window.location.href = 'login';
                        localStorage.removeItem('jwt_token');
                        localStorage.removeItem('user_name');
                        window.location.replace('login');
                        window.history.pushState(null, '', 'login');
                        window.addEventListener('popstate', function() {
                            window.history.pushState(null, '', 'login');
                        });
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            text: 'Terjadi kesalahan saat logout'
                        });
                    }
                });
            })
        }
    }

    function loadTable(query = '') {
        const token = localStorage.getItem('jwt_token');
        let url = query ? `${apiUrl}/search?q=${query}` : apiUrl;
        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            success: function(response) {
                $('#prodTab').empty();
                if (response.status && response.data) {
                    response.data.forEach(function(produk) {
                        $('#prodTab').append(`
                        <tr>
                            <td>${produk.id}</td>
                            <td>${produk.nama}</td>
                            <td>${produk.deskripsi}</td>
                            <td>${new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(produk.harga)}</td>
                            <td>
                                <button class="btn btn-danger" onclick="remove(${produk.id})">
                                    <i class='bx bxs-trash'></i>
                                </button>
                                <button class="btn btn-primary" onclick="editPage(${produk.id})">
                                    <i class='bx bx-edit'></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    });
                } else {
                    $('#prodTab').append(`
                    <tr>
                        <td colspan="5" class="text-center">No data available</td>
                    </tr>
                `);
                }
            },
            error: function(xhr) {
                Toast.fire({
                    icon: 'error',
                    title: 'Session expired, Please Login'
                });
            }
        });
    }

    $('#searchInput').on('input', function() {
        const query = $(this).val();
        loadTable(query);
    });

    let searchTimeout;
    $('#searchInput').on('input', function() {
        const query = $(this).val();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadTable(query);
        }, 300);
    });

    function remove(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                const token = localStorage.getItem('jwt_token');
                $.ajax({
                    url: `${apiUrl}/${id}`,
                    method: "DELETE",
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus'
                        }).then(() => {
                            loadTable();
                        })
                    },
                    error: function(xhr, status, error) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Session expired, Please login'
                        }).then(() => {
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
                                    window.location.href = 'login';
                                    localStorage.removeItem('jwt_token');
                                    localStorage.removeItem('user_name');
                                    window.location.replace('login');
                                    window.history.pushState(null, '', 'login');
                                    window.addEventListener('popstate', function() {
                                        window.history.pushState(null, '', 'login');
                                    });
                                },
                                error: function(xhr) {
                                    Toast.fire({
                                        icon: 'error',
                                        text: 'Terjadi kesalahan saat logout'
                                    });
                                }
                            });
                        })
                    }
                });
            } else if (xhr.status === 401) {
                Toast.fire({
                    icon: 'error',
                    title: 'Session Expired or aborted',
                }).then(() => {
                    localStorage.removeItem('jwt_token');
                    localStorage.removeItem('user_name');
                    window.location.replace('login');
                    window.history.pushState(null, '', 'login');
                    window.addEventListener('popstate', function() {
                        window.history.pushState(null, '', 'login');
                    });
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Session Expired'
                }).then(() => {
                    localStorage.removeItem('jwt_token');
                    localStorage.removeItem('user_name');
                    window.location.replace('login');
                    window.history.pushState(null, '', 'login');
                    window.addEventListener('popstate', function() {
                        window.history.pushState(null, '', 'login');
                    });
                })
            }
        });
    }

    $(document).ready(function() {
        loadTable();
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

        //Submit
        $('#addBtn').on('click', function() {
            $('#add-tab').slideToggle(120);
        });

        $('#formProduk').on('submit', function(e) {
            e.preventDefault();
            const token = localStorage.getItem('jwt_token');
            const harga = $('#harga').val().replace(/,/g, '');
            const formData = {
                nama: $('#nama').val(),
                deskripsi: $('#deskripsi').val(),
                harga: harga
            };
            $.ajax({
                url: 'http://10.21.1.125:8000/api/produk',
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.status) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Product added successfully'
                        }).then(() => {
                            loadTable();
                            $('#formProduk')[0].reset();
                            $('#add-tab').slideUp(120);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Failed to add product'
                        });
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Session Expired, Please Login'
                    }).then(() => {
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
                                window.location.href = 'login';
                                localStorage.removeItem('jwt_token');
                                localStorage.removeItem('user_name');
                                window.location.replace('login');
                                window.history.pushState(null, '', 'login');
                                window.addEventListener('popstate', function() {
                                    window.history.pushState(null, '', 'login');
                                });
                            },
                            error: function(xhr) {
                                Toast.fire({
                                    icon: 'error',
                                    text: 'Terjadi kesalahan saat logout'
                                });
                            }
                        });
                    });
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>