<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!-- Tracking Demo Card -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Tracking Demo
        </h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            Try these demo links to see tracking in action:
        </div>
        <div class="list-group mb-3">
            <a href="?source=facebook&medium=cpc&zone_id=123&cost=0.5" class="list-group-item list-group-item-action">
                <i class="bi bi-facebook text-primary me-2"></i>
                Facebook Ad Traffic (CPC $0.50)
            </a>
            <a href="?source=google&medium=cpm&zone_id=456&cost=1.2" class="list-group-item list-group-item-action">
                <i class="bi bi-google text-danger me-2"></i>
                Google Display Traffic (CPM $1.20)
            </a>
            <a href="?source=email&medium=newsletter&zone_id=789&cost=0.3&click_id=DEMO123" class="list-group-item list-group-item-action">
                <i class="bi bi-envelope-fill text-success me-2"></i>
                Email Newsletter (CPC $0.30 with Click ID)
            </a>
        </div>
        <button id="resetTracking" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Tracking
        </button>
    </div>
</div>

<!-- Confirmation Demo Card -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-check-circle me-2"></i>Visitor Register Demo
        </h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> Visitors can only be registered once per day. After successful registration, the button will be disabled until the cookie expires.<br/><br/>
            <i class="bi bi-arrow-repeat me-2"></i><b>To test again:</b> Clear your browser cookies, refresh the page, and try registering once more.
        </div>
        <button id="register" class="btn btn-primary">
            <i class="bi bi-person-check-fill me-2"></i> Register
        </button>
    </div>
</div>

<!-- Confirmation Demo Card -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-check-circle me-2"></i>Postback Demo
        </h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <p><i class="bi bi-info-circle me-2"></i> To test postbacks on the offerwall:</p>
            <ol>
                <li>Open the offerwall and click an offer</li>
                <li>Copy the <code>?click_id=xxxxxx</code> from the URL</li>
                <li>Paste it at the end of your current URL and press Enter</li>
                <li>Click the "Postback" button</li>
                <li>Check if the offer shows as completed in the offerwall</li>
            </ol>
            <p><strong><i class="bi bi-exclamation-circle me-2"></i>Note:</strong> Each click ID works only once. Successful postbacks remove the click ID.</p>
                
            <p><strong><i class="bi bi-shield-exclamation me-2"></i>Important:</strong> Only ACTPRO offers can be tested this way. Third-party offers use server-to-server (S2S) postbacks.</p>
        </div>
        <button id="postback" class="btn btn-primary">
            <i class="bi bi-person-check-fill me-2"></i> Postback
        </button>
    </div>
</div>

<!-- Offerwall Demo Card -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-wallet2 me-2"></i>Offerwall Demo
        </h3>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Complete offers to unlock the download button
        </div>
        <div class="text-center py-3">
            <img src="https://placehold.co/600x300?text=Your+Premium+Content" class="img-fluid rounded mb-3" alt="Premium Content">
            <p class="lead">Complete 2 offers to unlock this premium content</p>
            <button id="showOfferwall" class="btn btn-primary btn-lg">
                <i class="bi bi-unlock me-2"></i>Unlock Content
            </button>
        </div>
    </div>
</div>

<!-- Confirmation Demo Card -->
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-check-circle me-2"></i>Confirmation Demo
        </h3>
    </div>
    <div class="card-body">
        <p>After completing offers, get your confirmation code:</p>
        <button id="getConfirmation" class="btn btn-success">
            <i class="bi bi-shield-check me-2"></i>Get Confirmation Code
        </button>
    </div>
</div>



<script src="https://airaju94.github.io/cdn/actpro-obf.js?v=67fb36d50d86e"></script>
<script>
    ACTPRO.setup({
        endpoint: '<?php echo siteUrl() ?>/api',
        cleanUrl: true,
        cookie_name: 'ACTPRO_visitor',
        cookie_time: 7,
        postback: {
            network: 'ACTPRO',
            payout: 0
        },
        offerwall: {
            trigger: null,
            required_conversion: 2,
            checkInEvery: 15,
            title: 'Your File is Ready!',
            text: 'Complete 2 offers to unlock the download button.',
            buttonText: 'Download Now',
            buttonClick: () => {
                ACTPRO.popup('Download Button Clicked', 'You have clicked the download button.');
            },
            onComplete: null,
            showCloseButton: true
        }
    });
    ACTPRO.init();

    document.getElementById('register').addEventListener('click', function() {
        ACTPRO.register((res) => {
            ACTPRO.popup( 'Visitor Registered', 'Visitor registered successfully, visitor id is: <b>'+res.visitor_id+'</b>.' );
        });
    });

    document.getElementById('postback').addEventListener('click', function() {
        ACTPRO.px((res) => {
            res.status === 'success' && ACTPRO.popup( 'Postback Success', 'You conversion id is: <b>'+res.conv_id+'</b>.' );
        });
    });
    
    document.getElementById('resetTracking').addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });
    
    // These would be replaced with actual ACTPRO function calls
    document.getElementById('showOfferwall').addEventListener('click', function() {
        ACTPRO.offerwall();
    });
    
    document.getElementById('getConfirmation').addEventListener('click', function() {
        ACTPRO.confirm();
    });
</script>