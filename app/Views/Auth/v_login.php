<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login form</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= base_url('public/css/v_login.css') ?>">
</head>

<body>
    <div class="login-container" data-aos="fade-right">
        <div class="left-panel" data-aos="fade-left">
            <img src="<?= base_url('public/images/login.jpg') ?>" alt="login illustration" />
        </div>
        <div class="right-panel" data-aos="fade-right">
            <h2>Welcome to <strong>CrudApi</strong></h2>
            <p>Test website for api and iframe</p>
            <form id="loginForm" action="<?= base_url('login') ?>" method="POST">
                <div id="alert" class="alert alert-danger" role="alert" style="display: none;"></div>
                <div class="form-group">
                    <span class="icon bx bxs-user-rectangle"></span>
                    <input type="text" id="username" name="usernm" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <span class="icon bx bxs-key"></span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn" data-aos="fade-right">Log in</button>
            </form>
            <a href="<?= base_url('register') ?>" class="basic-link">Register</a>
        </div>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="<?= base_url('public/js/toast.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>


<script>
    AOS.init({
        duration: 800,
        once: true
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
                success: function(response) {
                    // window.location.replace('dashboard');
                    Toast.fire({
                        icon: 'success',
                        title: 'already loggendIn'
                    })
                }
            });
        }

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
    
            const username = $('#username').val();
            const password = $('#password').val();

            $.ajax({
                type: 'POST',
                url: 'http://10.21.1.125:8000/api/login',
                dataType: 'json',
                data: {
                    username,
                    password
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
                                withCredentials: true // 
                            },
                            success: function(sessionResponse) {
                                if (sessionResponse.status == true) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Login Success',
                                    }).then(() => {
                                        window.location.href = response.redirect || "<?= base_url('dashboard') ?>";
                                    });
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Login Failed',
                                        text: 'Something went wrong, please try again.'
                                    });
                                }
                            },
                            error: function(xhr){
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Login Failed',
                                    text: 'Password or username is incorrect'
                                });
                            }
                        });

                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: 'Something went wrong, please try again.'
                        })
                    }
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Password or username is incorrect'
                    });
                },
            });
        });
    });
</script>
</body>

</html>