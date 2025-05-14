<style>
    .navbar {
        transition: all 0.3s ease;
    }

    .navbar-brand {
        text-transform: capitalize;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        font-weight: bold;
        font-size: 1.5rem;
        margin-left: 20px;
        transition: all 0.3s ease;
    }

    #sidebar-toggler {
        z-index: 1050;
        position: fixed;
        top: 25px;
        background-color: #0044ff;
        color: rgb(255, 255, 255);
        border: none;
        border-radius: 20%;
        width: 40px;
        height: 40px;
        transition: all 0.3s ease;
    }

    .sidebar-closed #sidebar-toggler {
        left: 225px;
    }

    .sidebar-open #sidebar-toggler {
        left: 20px;
    }

    .sidebar-closed .navbar-brand {
        margin-left: 260px;
    }

    .sidebar-open .navbar-brand {
        margin-left: 60px;
    }

    .sidebar-open #sidebar-toggler {
        margin-left: 10px;
    }

    /* Tambahan untuk posisi dan animasi tombol rightSidebarToggler */
    #rightSidebarToggler {
        transition: all 0.3s ease;
        margin-right: 10px;
    }

    /* Saat sidebar kanan terbuka, geser tombol ke kiri */
    .right-sidebar-open #rightSidebarToggler {
        margin-right: 170px;
        /* Menyesuaikan lebar right sidebar */
    }
</style>

<nav class="navbar navbar-expand-lg bg-body-tertiary px-3" data-bs-theme="dark">
    <div class="container-fluid d-flex align-items-center">
        <button id="sidebar-toggler">
            <i class='bx bxs-left-arrow-circle'></i>
        </button>
        <div class="navbar-brand">
            CrudApi
        </div>
        <div class="collapse navbar-collapse" id="navbarNav">
        </div>
        <div class="justify-content-end">
            <!-- <button class="btn btn-danger" id="logoutButton">
                <i class="bx bx-power-off"></i>
            </button> -->
            <button class="btn btn-primary" id="rightSidebarToggler">
                <i class="bx bx-menu-alt-right"></i>
            </button>
        </div>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('sidebar-toggler').addEventListener('click', function() {
        const body = document.body;
        const icon = this.querySelector('i');

        if (body.classList.contains('sidebar-open')) {
            body.classList.remove('sidebar-open');
            body.classList.add('sidebar-closed');
            icon.classList.remove('bxs-left-arrow-circle');
            icon.classList.add('bxs-right-arrow-circle');
        } else {
            body.classList.add('sidebar-open');
            body.classList.remove('sidebar-closed');
            icon.classList.remove('bxs-right-arrow-circle');
            icon.classList.add('bxs-left-arrow-circle');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.body.classList.add('sidebar-open');
        const icon = document.querySelector('#sidebar-toggler i');
        icon.classList.remove('bxs-right-arrow-circle');
        icon.classList.add('bxs-left-arrow-circle');
    });

 
</script>