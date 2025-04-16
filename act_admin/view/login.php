<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<style>
    .login-container {
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
        padding: 10px;
    }

    .login-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .login-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.2rem;
        text-align: center;
    }

    .login-header h3 {
        font-weight: 700;
        margin-bottom: 0;
    }

    .login-body {
        padding: 2rem;
        background-color: var(--bs-tertiary-bg);
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
    }

    .btn-login {
        background-color: var(--primary-color);
        border: none;
        padding: 0.75rem;
        font-weight: 600;
        width: 100%;
        border-radius: 8px;
    }

    .btn-login:hover {
        background-color: var(--primary-hover);
    }

    .login-footer {
        text-align: center;
        padding: 1rem;
        background-color: var(--bs-tertiary-bg);
        border-top: 1px solid var(--bs-tertiary-color);
    }

    .brand-logo {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        display: block;
    }

    .brand-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .input-group-text {
        background-color: white;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .divider::before, .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e0e0e0;
    }

    .divider-text {
        padding: 0 1rem;
        color: #6c757d;
        font-size: 0.875rem;
    }
</style>
<div class="login-container">
    <div class="login-card mt-5">
        <div class="login-header">
            <span class="brand-logo mb-0">ACT PRO</span>
            <div class="brand-subtitle">Smart Tracking System</div>
        </div>
        <div class="login-body">
            <?php if( e::has() ): ?>
                <?php echo getMessage(); ?>
            <?php endif; ?>
            <form action="<?php echo Url::baseUrl() ?>/auth/login" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--bs-tertiary-bg)"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:var(--bs-tertiary-bg)"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-login mb-3">Login</button>
            </form>
        </div>
        <div class="login-footer">
            <small class="text-muted">No user login/signup here... :(</small>
        </div>
    </div>
</div>