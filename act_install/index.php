<?php
session_start();
// Define installation steps
$steps = [
    'welcome' => 'Welcome',
    'requirements' => 'System Check',
    'database' => 'Database Setup',
    'admin' => 'Admin Account',
    'settings' => 'App Settings',
    'complete' => 'Finish'
];

// Get current step
$currentStep = isset($_GET['step']) ? $_GET['step'] : 'welcome';
if (!array_key_exists($currentStep, $steps)) {
    $currentStep = 'welcome';
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($currentStep === 'database') {
        // Test database connection
        try {
            $db = new mysqli(
                $_POST['db_host'],
                $_POST['db_user'],
                $_POST['db_pass'],
                $_POST['db_name']
            );
            
            if ($db->connect_error) {
                throw new Exception("Connection failed: " . $db->connect_error);
            }
            
            // Store database config in session
            $_SESSION['db_config'] = [
                'host' => $_POST['db_host'],
                'user' => $_POST['db_user'],
                'pass' => $_POST['db_pass'],
                'name' => $_POST['db_name']
            ];
            
            header("Location: ?step=admin");
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif ($currentStep === 'admin') {
        // Validate admin credentials
        if (!empty($_POST['admin_user']) && !empty($_POST['admin_pass'])) {
            $_SESSION['admin_config'] = [
                'user' => $_POST['admin_user'],
                'pass' => password_hash($_POST['admin_pass'], PASSWORD_DEFAULT)
            ];
            header("Location: ?step=settings");
            exit;
        } else {
            $error = "Please provide both username and password";
        }
    } elseif ($currentStep === 'settings') {
        // Validate app settings
        if (!empty($_POST['app_name']) && !empty($_POST['timezone'])) {
            $_SESSION['app_config'] = [
                'name' => $_POST['app_name'],
                'timezone' => $_POST['timezone'],
                'fallback_url' => $_POST['fallback_url'] ?? 'https://aolgames.xyz/'
            ];
            header("Location: ?step=complete");
            exit;
        } else {
            $error = "Please provide all required settings";
        }
    }
}

if ($currentStep === 'complete') {
    // Write config file
    $configContent = "<?php\n\n/* Security Constant */\n"
        . "define('BASE', 'true');\n\n"
        . "// Database Configuration\n"
        . "define('DB_HOST', '" . addslashes($_SESSION['db_config']['host']) . "');\n"
        . "define('DB_USER', '" . addslashes($_SESSION['db_config']['user']) . "');\n"
        . "define('DB_PASS', '" . addslashes($_SESSION['db_config']['pass']) . "');\n"
        . "define('DB_NAME', '" . addslashes($_SESSION['db_config']['name']) . "');\n\n"
        . "// Admin Login Credentials\n"
        . "define('ADMIN_USERNAME', '" . addslashes($_SESSION['admin_config']['user']) . "');\n"
        . "define('ADMIN_PASSWORD', '" . addslashes($_SESSION['admin_config']['pass']) . "');\n\n"
        . "// Application Settings\n"
        . "define('APP_NAME', '" . addslashes($_SESSION['app_config']['name']) . "');\n"
        . "define('TIMEZONE', '" . addslashes($_SESSION['app_config']['timezone']) . "');\n"
        . "define('Root', __DIR__);\n"
        . "define('ROOT_DIR', '/'.strtolower(basename(Root)));\n"
        . "date_default_timezone_set(TIMEZONE);\n\n"
        . "// Tracker Settings\n"
        . "define('FALLBACK_URL', '" . addslashes($_SESSION['app_config']['fallback_url']) . "');\n\n"
        . "// Error Reporting\n"
        . "error_reporting(E_ALL);\n"
        . "ini_set('display_errors', 0);\n"
        . "ini_set('log_errors', 1);\n"
        . "ini_set('error_log', __DIR__ . '/error.log');\n";
    
    // Import database
    try {
        $db = new mysqli(
            $_SESSION['db_config']['host'],
            $_SESSION['db_config']['user'],
            $_SESSION['db_config']['pass'],
            $_SESSION['db_config']['name']
        );
        
        // // Read SQL file
        // $sql = file_get_contents('database.sql');
        // if ($sql === false) {
        //     throw new Exception("Could not read database.sql file");
        // }
        
        // // Execute SQL queries
        // if (!$db->multi_query($sql)) {
        //     throw new Exception("Database import failed: " . $db->error);
        // }
        
        // Write config file
        if (file_put_contents('../config.php', $configContent) === false) {
            throw new Exception("Could not write config file");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Check requirements for requirements step
if ($currentStep === 'requirements') {
    $requirements = [
        'PHP 7.4+' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'MySQLi Extension' => extension_loaded('mysqli'),
        'PDO Extension' => extension_loaded('pdo'),
        'config.php Writable' => is_writable('..') || (!file_exists('../config.php') && is_writable('..')),
        'install Directory Writable' => is_writable('.'),
    ];
    $allRequirementsMet = !in_array(false, $requirements, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACT PRO Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            /* Core Brand Colors */
            --primary-color: #2C5E91;       /* Authority Blue */
            --primary-hover: #1F4A7A;      /* Dark Navy */
            --secondary-color: #4F7CA9;    /* Approachable Light Blue */
            
            /* Accents & Status */
            --accent-color: #D4A76A;       /* Trust Gold (for CTAs) */
            --success-color: #3A8B7D;      /* Emerald Green */
            --warning-color: #E3B23C;      /* Amber Alert */
            --danger-color: #A63D40;       /* Maroon Red (subtle urgency) */
            --info-color: #5B8FB9;         /* Communication Blue */
            
            /* Neutrals */
            --dark-color: #1A2A3A;         /* Deep Corporate Navy */
            --light-color: #F5F7FA;        /* Professional White */
            --mid-gray: #6D7B8D;           /* Document Gray */
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 10px;
        }
        
        .installer-container {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .installer-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .installer-body {
            padding: 2rem;
        }
        
        .installer-footer {
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        
        .step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: bold;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--success-color);
            color: white;
        }
        
        .step-label {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .requirement-status {
            font-weight: bold;
        }
        
        .requirement-status.passed {
            color: var(--success-color);
        }
        
        .requirement-status.failed {
            color: var(--danger-color);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <div class="installer-header">
            <h2>ACT PRO Installation</h2>
            <p class="mb-0">Complete the installation in a few simple steps</p>
        </div>
        
        <div class="step-indicator mt-4">
            <?php foreach ($steps as $step => $label): ?>
                <?php
                $stepClass = '';
                $stepNumber = array_search($step, array_keys($steps)) + 1;
                
                if ($step === $currentStep) {
                    $stepClass = 'active';
                } elseif (array_search($step, array_keys($steps)) < array_search($currentStep, array_keys($steps))) {
                    $stepClass = 'completed';
                }
                ?>
                <div class="step <?= $stepClass ?>">
                    <div class="step-number"><?= $stepNumber ?></div>
                    <div class="step-label"><?= $label ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="installer-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($currentStep === 'welcome'): ?>
                <div class="text-center">
                    <h3 class="mb-4">Welcome to ACT PRO Installer</h3>
                    <p>This installer will guide you through setting up your ACT PRO application.</p>
                    <p>Please make sure you have imported your database and you have the database credentials ready before proceeding.</p>
                    
                    <div class="mt-4">
                        <a href="?step=requirements" class="btn btn-primary btn-lg">
                            Begin Installation <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
            <?php elseif ($currentStep === 'requirements'): ?>
                <h3 class="mb-4">System Requirements Check</h3>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Requirement</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requirements as $label => $passed): ?>
                                <tr>
                                    <td><?= htmlspecialchars($label) ?></td>
                                    <td class="requirement-status <?= $passed ? 'passed' : 'failed' ?>">
                                        <?= $passed ? 'Passed' : 'Failed' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($allRequirementsMet): ?>
                    <div class="alert alert-success">
                        All system requirements are met. You can proceed with the installation.
                    </div>
                    
                    <div class="text-end">
                        <a href="?step=database" class="btn btn-primary">
                            Continue <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        Some system requirements are not met. Please fix them before proceeding.
                    </div>
                <?php endif; ?>
                
            <?php elseif ($currentStep === 'database'): ?>
                <h3 class="mb-4">Database Configuration</h3>
                <p>Please enter your database connection details:</p>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="db_host" class="form-label">Database Host</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_user" class="form-label">Database Username</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_pass" class="form-label">Database Password</label>
                        <input type="password" class="form-control" id="db_pass" name="db_pass">
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_name" class="form-label">Database Name</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" required>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Test Connection & Continue <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
                
            <?php elseif ($currentStep === 'admin'): ?>
                <h3 class="mb-4">Admin Account Setup</h3>
                <p>Create your administrator account:</p>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="admin_user" class="form-label">Admin Username</label>
                        <input type="text" class="form-control" id="admin_user" name="admin_user" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="admin_pass" class="form-label">Admin Password</label>
                        <input type="password" class="form-control" id="admin_pass" name="admin_pass" required>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Continue <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
                
            <?php elseif ($currentStep === 'settings'): ?>
                <h3 class="mb-4">Application Settings</h3>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="app_name" class="form-label">Application Name</label>
                        <input type="text" class="form-control" id="app_name" name="app_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="timezone" name="timezone" required>
                            <optgroup label="Africa">
                                <option value="Africa/Cairo">Cairo</option>
                                <option value="Africa/Casablanca">Casablanca</option>
                                <option value="Africa/Johannesburg">Johannesburg</option>
                                <option value="Africa/Lagos">Lagos</option>
                                <option value="Africa/Nairobi">Nairobi</option>
                            </optgroup>
                            
                            <optgroup label="America">
                                <option value="America/Adak">Adak (Hawaii-Aleutian)</option>
                                <option value="America/Anchorage">Anchorage (Alaska)</option>
                                <option value="America/Chicago">Chicago (Central)</option>
                                <option value="America/Denver">Denver (Mountain)</option>
                                <option value="America/Detroit">Detroit (Eastern)</option>
                                <option value="America/Halifax">Halifax (Atlantic)</option>
                                <option value="America/Indiana/Indianapolis">Indianapolis (Eastern)</option>
                                <option value="America/Los_Angeles">Los Angeles (Pacific)</option>
                                <option value="America/Mexico_City">Mexico City</option>
                                <option value="America/New_York">New York (Eastern)</option>
                                <option value="America/Phoenix">Phoenix (MST)</option>
                                <option value="America/Santiago">Santiago</option>
                                <option value="America/Sao_Paulo">São Paulo</option>
                                <option value="America/St_Johns">St. John's (Newfoundland)</option>
                                <option value="America/Toronto">Toronto (Eastern)</option>
                                <option value="America/Vancouver">Vancouver (Pacific)</option>
                            </optgroup>
                            
                            <optgroup label="Asia">
                                <option value="Asia/Bangkok">Bangkok</option>
                                <option value="Asia/Beirut">Beirut</option>
                                <option value="Asia/Dhaka">Dhaka</option>
                                <option value="Asia/Dubai">Dubai</option>
                                <option value="Asia/Hong_Kong">Hong Kong</option>
                                <option value="Asia/Jakarta">Jakarta</option>
                                <option value="Asia/Jerusalem">Jerusalem</option>
                                <option value="Asia/Kabul">Kabul</option>
                                <option value="Asia/Karachi">Karachi</option>
                                <option value="Asia/Kathmandu">Kathmandu</option>
                                <option value="Asia/Kolkata">Kolkata</option>
                                <option value="Asia/Kuala_Lumpur">Kuala Lumpur</option>
                                <option value="Asia/Manila">Manila</option>
                                <option value="Asia/Riyadh">Riyadh</option>
                                <option value="Asia/Seoul">Seoul</option>
                                <option value="Asia/Shanghai">Shanghai</option>
                                <option value="Asia/Singapore">Singapore</option>
                                <option value="Asia/Taipei">Taipei</option>
                                <option value="Asia/Tashkent">Tashkent</option>
                                <option value="Asia/Tbilisi">Tbilisi</option>
                                <option value="Asia/Tehran">Tehran</option>
                                <option value="Asia/Tokyo">Tokyo</option>
                                <option value="Asia/Yangon">Yangon</option>
                            </optgroup>
                            
                            <optgroup label="Atlantic">
                                <option value="Atlantic/Azores">Azores</option>
                                <option value="Atlantic/Cape_Verde">Cape Verde</option>
                                <option value="Atlantic/Reykjavik">Reykjavik</option>
                            </optgroup>
                            
                            <optgroup label="Australia">
                                <option value="Australia/Adelaide">Adelaide</option>
                                <option value="Australia/Brisbane">Brisbane</option>
                                <option value="Australia/Darwin">Darwin</option>
                                <option value="Australia/Hobart">Hobart</option>
                                <option value="Australia/Melbourne">Melbourne</option>
                                <option value="Australia/Perth">Perth</option>
                                <option value="Australia/Sydney">Sydney</option>
                            </optgroup>
                            
                            <optgroup label="Europe">
                                <option value="Europe/Amsterdam">Amsterdam</option>
                                <option value="Europe/Athens">Athens</option>
                                <option value="Europe/Belgrade">Belgrade</option>
                                <option value="Europe/Berlin">Berlin</option>
                                <option value="Europe/Bratislava">Bratislava</option>
                                <option value="Europe/Brussels">Brussels</option>
                                <option value="Europe/Bucharest">Bucharest</option>
                                <option value="Europe/Budapest">Budapest</option>
                                <option value="Europe/Copenhagen">Copenhagen</option>
                                <option value="Europe/Dublin">Dublin</option>
                                <option value="Europe/Helsinki">Helsinki</option>
                                <option value="Europe/Istanbul">Istanbul</option>
                                <option value="Europe/Kiev">Kiev</option>
                                <option value="Europe/Lisbon">Lisbon</option>
                                <option value="Europe/London">London</option>
                                <option value="Europe/Madrid">Madrid</option>
                                <option value="Europe/Minsk">Minsk</option>
                                <option value="Europe/Moscow">Moscow</option>
                                <option value="Europe/Oslo">Oslo</option>
                                <option value="Europe/Paris">Paris</option>
                                <option value="Europe/Prague">Prague</option>
                                <option value="Europe/Riga">Riga</option>
                                <option value="Europe/Rome">Rome</option>
                                <option value="Europe/Sofia">Sofia</option>
                                <option value="Europe/Stockholm">Stockholm</option>
                                <option value="Europe/Tallinn">Tallinn</option>
                                <option value="Europe/Vienna">Vienna</option>
                                <option value="Europe/Vilnius">Vilnius</option>
                                <option value="Europe/Warsaw">Warsaw</option>
                                <option value="Europe/Zurich">Zurich</option>
                            </optgroup>
                            
                            <optgroup label="Pacific">
                                <option value="Pacific/Auckland">Auckland</option>
                                <option value="Pacific/Chatham">Chatham</option>
                                <option value="Pacific/Fiji">Fiji</option>
                                <option value="Pacific/Guam">Guam</option>
                                <option value="Pacific/Honolulu">Honolulu</option>
                                <option value="Pacific/Majuro">Majuro</option>
                                <option value="Pacific/Noumea">Noumea</option>
                                <option value="Pacific/Pago_Pago">Pago Pago</option>
                                <option value="Pacific/Port_Moresby">Port Moresby</option>
                                <option value="Pacific/Tahiti">Tahiti</option>
                                <option value="Pacific/Tongatapu">Tongatapu</option>
                            </optgroup>
                            
                            <optgroup label="UTC">
                                <option value="UTC" selected>UTC (Default)</option>
                                <option value="UTC+12">UTC+12</option>
                                <option value="UTC+11">UTC+11</option>
                                <option value="UTC+10">UTC+10</option>
                                <option value="UTC+9">UTC+9</option>
                                <option value="UTC+8">UTC+8</option>
                                <option value="UTC+7">UTC+7</option>
                                <option value="UTC+6">UTC+6</option>
                                <option value="UTC+5">UTC+5</option>
                                <option value="UTC+4">UTC+4</option>
                                <option value="UTC+3">UTC+3</option>
                                <option value="UTC+2">UTC+2</option>
                                <option value="UTC+1">UTC+1</option>
                                <option value="UTC-1">UTC-1</option>
                                <option value="UTC-2">UTC-2</option>
                                <option value="UTC-3">UTC-3</option>
                                <option value="UTC-4">UTC-4</option>
                                <option value="UTC-5">UTC-5</option>
                                <option value="UTC-6">UTC-6</option>
                                <option value="UTC-7">UTC-7</option>
                                <option value="UTC-8">UTC-8</option>
                                <option value="UTC-9">UTC-9</option>
                                <option value="UTC-10">UTC-10</option>
                                <option value="UTC-11">UTC-11</option>
                                <option value="UTC-12">UTC-12</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fallback_url" class="form-label">Fallback URL</label>
                        <input type="url" class="form-control" id="fallback_url" name="fallback_url" value="https://aolgames.xyz/">
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Continue <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
                
            <?php elseif ($currentStep === 'complete'): ?>
                <div class="text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">Installation Complete!</h3>
                    <p class="mb-4">ACT PRO has been successfully installed and configured.</p>
                    
                    <div class="alert alert-warning">
                        <strong>Important:</strong> For security reasons, please delete the "install" directory.
                    </div>
                    
                    <div class="mt-4">
                        <a href="/act_admin/" class="btn btn-primary btn-lg">
                            Go to Application <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="installer-footer">
            <?php if ($currentStep !== 'welcome' && $currentStep !== 'complete'): ?>
                <a href="?step=<?= array_keys($steps)[array_search($currentStep, array_keys($steps)) - 1] ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            
            <?php if ($currentStep === 'requirements' && $allRequirementsMet): ?>
                <a href="?step=database" class="btn btn-primary">
                    Continue <i class="bi bi-arrow-right"></i>
                </a>
            <?php elseif ($currentStep === 'database' || $currentStep === 'admin' || $currentStep === 'settings'): ?>
                <button type="submit" form="install-form" class="btn btn-primary">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>