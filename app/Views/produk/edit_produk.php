<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Edit Produk</h3>
            <a href="<?= base_url('produk') ?>" class="btn btn-danger">
                <i class='bx bxs-left-arrow-circle'></i>
                Kembali
            </a>
        </div>
        <div class="card-body">
            <form id="editForm">
                <input type="hidden" id="productId">
                <div class="form-group mb-3">
                    <label for="nama">Nama produk</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="form-group mb-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class='bx bxs-save'></i>
                    Save</button>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const token = localStorage.getItem('jwt_token');
    const productId = localStorage.getItem('edit_produk_id');
    const apiUrl = 'http://10.21.1.125:8000/api/produk';

    function loadProductData() {
        $.ajax({
            url: `${apiUrl}/${productId}`,
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.status && response.data) {
                    const product = response.data;
                    $('#productId').val(product.id);
                    $('#nama').val(product.nama);
                    $('#deskripsi').val(product.deskripsi);
                    $('#harga').val(product.harga);
                } else {
                    throw new Error('Invalid response format');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to load product data';
                if (xhr.status === 401) {
                    localStorage.removeItem('jwt_token');
                    window.location.href = '<?= base_url('produk') ?>';
                    return;
                }
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Toast.fire({
                    icon: 'error',
                    title: errorMessage
                }).then(() => {
                    window.location.href = '<?= base_url('produk') ?>';
                });
            }
        });
    }

    $(document).ready(function() {
        if (!productId) {
            Toast.fire({
                icon: 'error',
                title: 'No product selected for editing'
            }).then(() => {
                window.location.href = '<?= base_url('produk') ?>';
            });
            return;
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

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const harga = $('#harga').val().replace(/,/g, '');
        const formData = {
            nama: $('#nama').val(),
            deskripsi: $('#deskripsi').val(),
            harga: harga
        };
        const token = localStorage.getItem('jwt_token');
        $.ajax({
            url: `${apiUrl}/${productId}`,
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.status) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Product updated successfully'
                    }).then(() => {
                        localStorage.removeItem('edit_produk_id');
                        window.location.href = '<?= base_url('produk') ?>';
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Session Expired or aborted',
                    });
                }
            },
            error: function(xhr) {
                Toast.fire({
                    title: 'Session expired, Please login',
                    icon: 'error'
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
    });

    loadProductData();
</script>


<?= $this->endSection() ?>