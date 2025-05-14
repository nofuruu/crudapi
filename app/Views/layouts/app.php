<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css">
</head>

<style>
    body {
        background-color: #f5f7fa;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        margin-left: 30px;
        background-color: #1f2937;
    }
</style>

<body>

    <!-- Navbar -->
    <?= view('partials/navbar') ?>


    <?= view('partials/sidebar') ?>

    <?= view('partials/right-sidebar') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<script src="<?= base_url('public/js/toast.js') ?>"></script>

</html>