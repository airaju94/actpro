<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title ?> - <?php echo APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo Url::baseUrl() ?>/app/view/css/style.css?v=1.1" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://airaju94.github.io/cdn/actpro-obf.js?v=67ffaa5954263"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            ACTPRO.setup({
                endpoint: '<?php echo Url::baseUrl() ?>/api',
                cleanUrl: true,
                offerwall: {
                    trigger: null,
                    required_conversion: 1,
                    checkInEvery: 15,
                    title: 'Unlock the Mystery!!',
                    text: 'Complete a simple offer and discover something unexpected.',
                    buttonText: 'Reveal the Mystery',
                    buttonClick: () => {
                        ACTPRO.popup( 'Oh Noooo!', 'You are our <b>'+ACTPRO.params.visitor_id+'</b> number visitor, but we are looking for <b>'+(ACTPRO.params.visitor_id + Math.round( Math.random() * 100 ))+'</b> visitor.<br /><br/> Better luck next time!' );
                        // Trigger on page scroll
                        let lastScroll = 0;
                        window.addEventListener('scroll', () => {
                            if (window.scrollY > lastScroll + 100) {
                                triggerSurprise();
                                lastScroll = window.scrollY;
                            }
                        });

                        // Trigger after 15 seconds of inactivity
                        let inactivityTimer = setTimeout(triggerSurprise, 10000);
                        window.addEventListener('mousemove', () => {
                            clearTimeout(inactivityTimer);
                            inactivityTimer = setTimeout(triggerSurprise, 10000);
                        });
                    },
                    onComplete: null,
                    showCloseButton: true
                }
            });
            ACTPRO.init();
            ACTPRO.register();
        })
    </script>
<?php if( isset( $header_code ) ): ?>
    <?php echo $header_code ?>
<?php endif; ?>
    <script>
        const baseUrl = '<?php echo Url::baseUrl() ?>';
    </script>
</head>
<body data-bs-theme="<?php echo Cookie::get('actpro_theme') ?? 'light'; ?>">
<?php if( !self::$noHeader ): ?>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">YTSBLOG</div>
                <div class="nav-links">
                    <a href="#" style="color: white; text-decoration: none; font-weight: 500;">Why?</a>
                </div>
            </nav>
        </div>
    </header>
<?php endif; ?>