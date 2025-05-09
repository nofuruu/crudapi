<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .left-panel,
        .right-panel {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left-panel {
            background-color: #ffffff;
            margin-right: 20px;
        }

        .right-panel {
            background-color: #ffffff;
            margin-left: 20px;
        }

        .right-panel h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .right-panel p {
            font-size: 16px;
            color: #666;
        }

        .otp-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ccc;
            border-radius: 8px;
            outline: none;
        }

        .otp-input:focus {
            border-color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container" data-aos="fade-left">
        <div class="left-panel" data-aos="fade-right">
            <img src="<?= base_url('public/images/otp.jpg') ?>" alt="Ilustrasi Verifikasi OTP" class="img-fluid">
        </div>
        <div class="right-panel" data-aos="fade-left">
            <h2>Verifikasi Sistem</h2>
            <p>Kami telah mengirimkan kode 6 digit ke <strong id="email"><strong></p>
            <div class="timer"></div>
            <div class="otp-container">
                <input type="text" id="otp1" class="otp-input" maxlength="1" oninput="moveFocus(this, 'otp2')" />
                <input type="text" id="otp2" class="otp-input" maxlength="1" oninput="moveFocus(this, 'otp3')" />
                <input type="text" id="otp3" class="otp-input" maxlength="1" oninput="moveFocus(this, 'otp4')" />
                <input type="text" id="otp4" class="otp-input" maxlength="1" oninput="moveFocus(this, 'otp5')" />
                <input type="text" id="otp5" class="otp-input" maxlength="1" oninput="moveFocus(this, 'otp6')" />
                <input type="text" id="otp6" class="otp-input" maxlength="1" oninput="submitOtp()" />
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary" id="verifyBtn">Verifikasi</button>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="<?= base_url('public/js/toast.js') ?>"></script>

<script>
    const email = localStorage.getItem('email');

    AOS.init({
        duration: 800,
        once: true
    });

    function moveFocus(current, nextFieldId) {
        if (current.value.length === current.maxLength) {
            document.getElementById(nextFieldId).focus();
        }
    }

    function getOtp() {
        return [
            $('#otp1').val(),
            $('#otp2').val(),
            $('#otp3').val(),
            $('#otp4').val(),
            $('#otp5').val(),
            $('#otp6').val()
        ].join('');
    }

    function verifyOtp(otp) {
        $.ajax({
            type: 'POST',
            url: 'http://10.21.1.125:8000/api/verifyOtp',
            data: {
                otp: otp,
                email: email
            },
            success: function(response) {
                if (response.status === true && response.access_token) {
                    localStorage.setItem('jwt_token', response.access_token);
                    localStorage.setItem('user_id', response.user.id);
                    localStorage.setItem('user_name', response.user.name);

                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('LoginController/setSession') ?>',
                        data: {
                            user_id: response.user.id,
                            user_name: response.user.name,
                            jwt_token: response.access_token
                        },
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function(sessionResponse) {
                            if (sessionResponse.status === true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Verifikasi berhasil',
                                    text: 'Login berhasil'
                                }).then(() => {
                                    window.location.href = response.redirect || "<?= base_url('dashboard') ?>";
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Gagal set session',
                                    text: sessionResponse.message || 'Coba lagi nanti'
                                });
                            }
                        },
                        error: function(xhr) {
                            notify('error', xhr.responseText || 'Gagal set session');
                            // Toast.fire({
                            //     icon: 'error',
                            //     title: 'Gagal set session (server error)',
                            //     text: xhr.responseText || 'Terjadi kesalahan server'
                            // });
                        }
                    });
                } else {
                    notify('error', response.message || 'Gagal set session');
                }
            },
            error: function(xhr) {
                notify('error', xhr.responseJSON?.message || 'Gagal set session');
            }
        });
    }

    let timeRemaining = 5 * 60; // 5 menit dalam detik

    function updateTimer() {
        let minutes = Math.floor(timeRemaining / 60);
        let seconds = timeRemaining % 60;

        // Format menit dan detik
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        // Menampilkan waktu yang dihitung mundur
        $('.timer').text(minutes + ':' + seconds);

        if (timeRemaining > 0) {
            timeRemaining--; // Kurangi waktu setiap detik
        } else {
            clearInterval(timerInterval); // Berhenti menghitung mundur jika waktu habis
            alert("Waktu habis"); // Notifikasi jika waktu habis
        }
    }

    function submitOtp() {
        const otp = getOtp();
        verifyOtp(otp);
    }

    $(document).ready(function() {
        let timerInterval = setInterval(updateTimer, 1000);
        const email = localStorage.getItem('email');
        if (email) {
            document.getElementById('email').textContent = email;
        }

        $('#verifyBtn').on('click', function() {
            const otp = getOtp();
            verifyOtp(otp);
        });


    });
</script>

</html>