<style>
    #rightSidebar {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: 160px;
        background-color: #343a40;
        padding: 60px 15px 20px;
        overflow-y: auto;
        transition: transform 0.3s ease-in-out;
        z-index: 999;
    }

    #rightSidebar.closed-right {
        transform: translateX(100%);
    }

    #rightSidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #rightSidebar ul li {
        margin-bottom: 12px;
    }

    .action-btn {
        width: 100%;
        background-color: #1B56FD;
        color: white;
        border: none;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 14px;
        display: flex;
        align-items: center;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .action-btn i {
        margin-right: 8px;
        font-size: 16px;
    }

    .action-btn:hover {
        background-color:rgb(58, 70, 105);
    }

    .logout-btn {
        width: 100%;
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 14px;
        display: flex;
        align-items: center;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .logout-btn i {
        margin-right: 8px;
        font-size: 16px;
    }

    .logout-btn:hover {
        background-color:rgb(116, 34, 42);
    }

    .right-toggler {
        position: fixed;
        top: 20px;
        right: 180px;
        background: #ffffff;
        border: 1px solid #ccc;
        border-radius: 50%;
        padding: 8px;
        cursor: pointer;
        z-index: 1001;
        transition: right 0.3s;
    }

    #rightSidebar.closed-right~.right-toggler {
        right: 10px;
    }
</style>

<div id="rightSidebar" class="closed-right">
    <ul>
        <li>
            <button class="action-btn"><i class='bx bx-cog'></i> <span class="label">Settings</span></button>
        </li>
        <li>
            <button class="action-btn"><i class='bx bx-plus'></i> <span class="label">Add New</span></button>
        </li>
        <li>
            <button class="logout-btn" id="logoutButton"><i class="bx bx-power-off"></i><span>Logout</span></button>
        </li>
        <li>
            <button class="action-btn"><i class='bx bx-help-circle'></i> <span class="label">Help</span></button>
        </li>
    </ul>
</div>

<script>
    const rightSidebar = document.getElementById('rightSidebar');
    const rightToggler = document.getElementById('rightSidebarToggler');

    rightToggler.addEventListener('click', () => {
        const icon = rightToggler.querySelector('i');
        rightSidebar.classList.toggle('closed-right');

        // Toggle class di body untuk menggeser tombol
        document.body.classList.toggle('right-sidebar-open');

        // Ganti ikon tombol
        if (rightSidebar.classList.contains('closed-right')) {
            icon.classList.remove('bx-chevron-right');
            icon.classList.add('bx-menu-alt-right');
        } else {
            icon.classList.remove('bx-menu-alt-right');
            icon.classList.add('bx-chevron-right');
        }
    });



    $('#logoutButton').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'question',
            text: 'Anda yakin ingin logout?',
            showCancelButton: true,
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Tidak',
            confirmButtonColor: 'rgb(53, 208, 45)',
            cancelButtonColor: 'rgb(255, 0, 0)',
        }).then((result) => {
            if (result.isConfirmed) {
                const token = localStorage.getItem('jwt_token');
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
        });
    });
</script>