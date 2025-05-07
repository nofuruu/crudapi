<style>
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        background-color: #343a40;
        transition: width 0.3s;
        overflow-x: hidden;
        z-index: 1000;
    }

    #sidebar.closed {
        width: 55px;
    }

    #sidebar ul {
        list-style: none;
        padding: 0;
        /* margin-top: 60px; */
    }

    #sidebar ul li {
        color: #fff;
        padding: 12px 20px;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    #sidebar ul li:hover {
        background-color: #495057;
    }

    #sidebar ul li i {
        margin-right: 10px;
        min-width: 20px;
        text-align: center;
    }

    .submenu {
        display: none;
        margin-top: -5px;
        margin-left: 15px;
        padding-left: 25px;
        border-left: 2px solid rgba(255, 255, 255, 0.1);
    }

    .submenu li {
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .submenu li a {
        color: #e0e0e0;
        text-decoration: none;
        /* Hapus underline */
        display: block;
    }

    .submenu li a:hover {
        color: #ffffff;
        text-decoration: none;
    }


    #sidebar.closed ul li span.label {
        display: none;
    }

    #sidebar.closed .submenu {
        display: none !important;
    }

    #dashboardIcon {
        margin-top: 60px;
    }
</style>



<div id="sidebar" class="closed">
    <ul>
        <li id="dashboardIcon">
            <a href="<?= base_url('dashboard') ?>" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <div class="menu-icon">
                    <i class='bx bx-home'></i>
                    <span class="label">Dashboard</span>
                </div>
            </a>
        </li>

        <li onclick="toggleSubmenu('produkSubmenu')">
            <div class="menu-icon">
                <i class='bx bx-box'></i>
                <span class="label">Product</span>
            </div>
            <i class='bx bx-plus'></i>
        </li>
        <!-- Submenu -->
        <ul class="submenu" id="produkSubmenu">
            <li><a href="<?= base_url('produk') ?>">Manage product</a></li>
            <li><a href="<?= base_url('search') ?>">Search product</a></li>
        </ul>


        <li onclick="toggleSubmenu('deliverSubmenu')">
            <div class="menu-icon">
                <i class="bx bx-package"></i>
                <span class="label">Delivery</span>
            </div>
            <i class="bx bx-plus"></i>
        </li>
        <ul class="submenu" id="deliverSubmenu">
            <li><a href="<?= base_url('delivery') ?>">Apply Delivery</a></li>
            <li><a href="<?= base_url('deliveryapp') ?>">Delivery Approval</a></li>
        </ul>

        <li onclick="toggleSubmenu('salesSubmenu')">
            <div class="menu-icon">
                <i class="bx bx-chart"></i>
                <span class="label">Sales</span>
            </div>
            <i class="bx bx-plus"></i>
        </li>
        <ul class="submenu" id="salesSubmenu">
            <li><a href="<?= base_url('sales') ?>">Sales Order</a></li>
            <li><a href="<?= base_url('salesapp') ?>">Sales Approval</a></li>
        </ul>


        <li onclick="toggleSubmenu('vehicleSubmenu')">
            <div class="menu-icon">
                <i class="bx bxs-truck"></i>
                <span class="label">Vehicle</span>
            </div>
            <i class="bx bx-plus"></i>
        </li>
        <ul class="submenu" id="vehicleSubmenu">
            <li><a href="<?= base_url('vehicle')?>">Peminjaman Kendaraan</a></li>
            <li><a href="<?= base_url('vehicleapp')?>">Approval Peminjaman Kendaraan</a></li>
        </ul>
    </ul>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggler = document.getElementById('sidebar-toggler');
    toggler.addEventListener('click', () => {
        sidebar.classList.toggle('closed');
    });

    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }
</script>