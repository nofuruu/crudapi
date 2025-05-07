<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<head>
	<title>Dashboard</title>
	<link rel="stylesheet" href="<?= base_url('public/css/v_dashboard.css') ?>">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<div class="container mt-4">
	<div class="header">
		<h3>Welcome to <strong>CrudApi</strong>, <span id="userName"></span></h3>
	</div>
	<div class="divider"></div>
	<div class="content mt-3">
		<div class="taskbar row g-3 mb-4">
			<div class="col-md-4">
				<div class="card card-item mild-blue d-flex align-items-center">
					<div class="icon-large me-3">
						<i class="fas fa-book"></i>
					</div>
					<div class="menu-info">
						<h6 class="menu-title">Note</h6>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card card-item mild-green d-flex align-items-center">
					<div class="icon-large me-3">
						<i class="fas fa-user"></i>
					</div>
					<div class="menu-info">
						<h6 class="menu-title">Manage User</h6>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="card card-item mild-purple d-flex align-items-center">
					<div class="icon-large me-3">
						<i class="fas fa-box"></i>
					</div>
					<div class="menu-info">
						<h6 class="menu-title">Dropship</h6>
						<!-- <p class="menu-desc">Cek dan kelola pengiriman barang</p> -->
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 mb-4">
				<div class="card p-3 shadow-sm">
					<h5 class="card-title">Jumlah Produk</h5>
					<canvas id="productChart"></canvas>
				</div>
			</div>

			<div class="col-md-6 mb-4">
				<div class="card p-3 shadow-sm">
					<h5 class="card-title">Grafik Penjualan</h5>
					<canvas id="userChart"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	const productChart = new Chart(document.getElementById('productChart'), {
		type: 'doughnut',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr'],
			datasets: [{
				label: 'Jumlah Produk',
				data: [12, 19, 3, 5],
				backgroundColor: [
					'rgba(75, 192, 192, 0.6)',
					'rgba(255, 99, 132, 0.6)',
					'rgba(255, 206, 86, 0.6)',
					'rgba(153, 102, 255, 0.6)'
				],
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'top'
				},
				title: {
					display: true,
					text: 'Grafik Jumlah Produk'
				}
			}
		}
	});

	const userChart = new Chart(document.getElementById('userChart'), {
		type: 'line',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr'],
			datasets: [{
				label: 'Jumlah Penjualan',
				data: [12, 19, 3, 5],
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				borderColor: 'rgba(75, 192, 192, 1)',
				borderWidth: 2,
				tension: 0.3,
				fill: true
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'top'
				},
				title: {
					display: true,
					text: 'Grafik Penjualan'
				}
			}
		}
	});
</script>





























<!-- Javascript -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const userName = localStorage.getItem('user_name');
		if (userName) {
			document.getElementById('userName').textContent = userName;
		}
	});


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
			Swal.fire({
				icon: 'error',
				title: 'Sesi anda sudah habis',
				text: ' Silahkan login kembali',
				confirmButtonColor: '#328E6E',
				confirmButtonText: 'Login',
				showCloseButton: true,
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
	})
</script>

<?= $this->endSection() ?>