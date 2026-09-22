<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hayfa Madina | Login</title>

    <link rel="icon" href="favicon.ico" type="image/ico">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?= base_url() ?>assets/backend/plugins/sb2/vendor/fontawesome-free/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url() ?>assets/backend/plugins/login.css">
</head>

<body>

    <div class="login-wrapper">

        <!-- Background Glow -->
        <div class="glow glow-1"></div>
        <div class="glow glow-2"></div>

        <div class="login-container">

            <!-- =====================================
                 LEFT BRAND
            ====================================== -->

            <div class="login-brand">

            <!-- Animated Background -->
            <div class="animated-bg">

                <span class="particle particle-1"></span>
                <span class="particle particle-2"></span>
                <span class="particle particle-3"></span>
                <span class="particle particle-4"></span>
                <span class="particle particle-5"></span>
                <span class="particle particle-6"></span>
                <span class="particle particle-7"></span>
                <span class="particle particle-8"></span>

                <div class="floating-orb orb-1"></div>
                <div class="floating-orb orb-2"></div>

                <div class="light-wave wave-1"></div>
                <div class="light-wave wave-2"></div>

            </div>

                <div class="brand-circle"></div>

                <div class="brand-content">

                    <!-- GANTI PATH LOGO SESUAI FILE LOGO LU -->
                    <img
                        src="<?= base_url() ?>assets/backend/img/hayfamadina-white.png"
                        alt="Hayfa Madina"
                        class="logo">

                    <div class="brand-line"></div>

                    <div class="brand-title">
                        Hayfa Madina
                    </div>

                    <div class="brand-description">
                        #iniTagLineNya
                    </div>
                    <div class="brand-description">
                    #TagLineLagi
                    </div>

                </div>

            </div>


            <!-- =====================================
                 RIGHT LOGIN
            ====================================== -->

            <div class="login-form-wrapper">

                <div class="login-form">

                    <div class="welcome">

                        <h2>Welcome Back</h2>

                        <p>
                            Sign in to continue to your dashboard.
                        </p>

                    </div>


                    <!-- Flash Message -->

                    <?php if ($this->session->flashdata('message')): ?>

                        <div class="message text-center">
                            <?= $this->session->flashdata('message'); ?>
                        </div>

                    <?php endif; ?>


                    <form action="<?= base_url('auth/login') ?>" method="post">

                        <!-- Username -->

                        <div class="form-group">

                            <label
                                for="username"
                                class="form-label">

                                Username

                            </label>

                            <div class="input-wrapper">

                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    class="form-control-custom"
                                    placeholder="Enter your username"
                                    autocomplete="username"
                                    required>

                                <i class="fas fa-user input-icon"></i>

                            </div>

                        </div>


                        <!-- Password -->

                        <div class="form-group">

                            <label
                                for="password"
                                class="form-label">

                                Password

                            </label>

                            <div class="input-wrapper">

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control-custom"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required>

                                <i class="fas fa-lock input-icon"></i>

                                <button
                                    type="button"
                                    class="password-toggle"
                                    id="passwordToggle"
                                    aria-label="Show password">

                                    <i
                                        class="fas fa-eye"
                                        id="passwordIcon"></i>

                                </button>

                            </div>

                        </div>


                        <!-- Login Button -->

                        <button
                            type="submit"
                            class="login-button">

                            <span>Sign In</span>

                            <i class="fas fa-arrow-right"></i>

                        </button>

                    </form>


                    <div class="login-footer">

                        © <?= date('Y') ?> Hayfa Madina.
                        All rights reserved.

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- jQuery -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <script>

        $(document).ready(function () {

            $('#passwordToggle').on('click', function () {

                const password = $('#password');
                const icon = $('#passwordIcon');

                if (password.attr('type') === 'password') {

                    password.attr('type', 'text');

                    icon.removeClass('fa-eye');
                    icon.addClass('fa-eye-slash');

                    $(this).attr(
                        'aria-label',
                        'Hide password'
                    );

                } else {

                    password.attr('type', 'password');

                    icon.removeClass('fa-eye-slash');
                    icon.addClass('fa-eye');

                    $(this).attr(
                        'aria-label',
                        'Show password'
                    );

                }

            });

        });

    </script>

</body>

</html>