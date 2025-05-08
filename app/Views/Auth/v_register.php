<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('public/css/v_register.css') ?>">
    <title>Registration Form</title>
</head>
<body>
    <div class="container">
        <div class="panel">
            <iframe id="laravelIframe" src="http://10.21.1.125:8000" onload="resizeIframe()"></iframe>
        </div>
    </div>

    <div id="loadingOverlay" style="display: none;">
        <div class="loader-container">
            <div class="dot-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p>Loading, please wait...</p>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="<?= base_url('public/js/toast.js') ?>"></script>

<script>
    window.addEventListener('message', function(event) {
        if (event.data.event === 'true') {
            document.getElementById('loadingOverlay').style.display = 'flex';
            setTimeout(function() {
                window.location.href = "<?= base_url('/login') ?>";
            }, 2000);
        }
    });

    function showLoading() {
        document.getElementById("loadingOverlay").style.display = "flex";
    }

    function hideLoading() {
        document.getElementById("loadingOverlay").style.display = "none";
    }

    function resizeIframe() {
        var iframe = document.getElementById('laravelIframe');
        try {
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        } catch (e) {
            console.warn("Tidak bisa akses isi iframe karena domain berbeda (CORS).");
            iframe.style.height = '100vh';
        }
    }
</script>

</html>