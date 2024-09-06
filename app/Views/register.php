<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>


    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-4/assets/css/registration-4.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="img/favicon/warehouse.png">

</head>

<body>
    <!-- Registration 4 - Bootstrap Brain Component -->
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?= base_url('img/login-img.jpg') ?>" alt="BootstrapBrain Logo">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h2 class="h3">Registration</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                                    </div>
                                </div>

                                <?php if (session()->getFlashdata('message')) : ?>
                                    <div style="color: green;">
                                        <?= session()->getFlashdata('message') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($validation)) : ?>
                                    <div style="color: red;">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form action="/register/store" method="post">

                                <?= csrf_field() ?>
                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" id="username" value="<?= old('username') ?>" placeholder="Username" required>
                                    </div>
                                    <div class=" col-12">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?= old('email') ?>" placeholder="name@example.com" required>
                                    </div>
                                    <div class=" col-12">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <div class=" col-12">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="first_name" class="form-control" name="first_name" id="first_name" value="<?= old('first_name') ?>" required>
                                    </div>
                                    <div class=" col-12">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="last_name" class="form-control" name="last_name" id="last_name" value="<?= old('last_name') ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                        <select id="role_id" name="role_id" class="form-select" name="role_id" required>
                                            <option selected disabled> -- Pilih Role Anda -- </option>
                                            <option value="1" <?= old('role_id') == 1 ? 'selected' : '' ?>>Administrator</option>
                                            <option value="2" <?= old('role_id') == 2 ? 'selected' : '' ?>>Manager</option>
                                            <option value="3" <?= old('role_id') == 3 ? 'selected' : '' ?>>Staff Gudang</option>
                                            <option value="4" <?= old('role_id') == 4 ? 'selected' : '' ?>>Kepala Gudang</option>
                                            <!-- Tambahkan opsi lain sesuai dengan data roles yang tersedia -->
                                        </select><br>
                                    </div>
                                    <div class="col-12">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-select" name="status" required>
                                            <option selected disabled> -- Pilih Status Anda -- </option>
                                            <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            <option value="banned" <?= old('status') == 'banned' ? 'selected' : '' ?>>Banned</option>
                                        </select><br>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="submit">Sign up</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <p class="m-0 text-secondary text-center">Already have an account? <a href="<?= base_url('login') ?>" class="link-primary text-decoration-none">Sign in</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>