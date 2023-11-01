<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="<?= base_url(); ?>" class="h1"><b><?= $webname; ?></b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?= $this->session->flash_data('message');  ?>
            <form action="<?= base_url('su/login'); ?>" method="post">
                <?php
                $csrf = array(
                    'name' => $this->security->get_csrf_token_name(),
                    'hash' => $this->security->get_csrf_hash()
                );
                ?>
                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="user" placeholder="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <!-- captcha -->
                <div class="input-group mb-3">
                    <p>Submit the word you see below:</p>
                    <div class="row gx-2">
                        <div class="col-6"><?= $capImage; ?></div>
                        <div class="col-6"><input type="text" name="captcha" value="" class="form-control" required /></div>
                        <!-- <div class="col-1"><a preventDefault() id="recaptcha" class="btn btn-sm btn-success"><i class="fas fa-sync"></i></a> -->
                        <!-- </div> -->
                    </div>

                </div>
                <!-- captcha -->
                <div class="row mt-3">
                    <div class="col-8">

                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- <div class="social-auth-links text-center mt-2 mb-3">
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div> -->
            <!-- /.social-auth-links -->

            <p class="mb-1">
                <a href="<?= base_url('auth/forgot'); ?>">I forgot my password</a>
            </p>
            <p class="mb-0">
                <a href="<?= base_url('auth/register'); ?>" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->