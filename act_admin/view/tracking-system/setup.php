<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-sliders me-2"></i> ACTPRO Setup Code Generator</h4>
    </div>
    <div class="card-body">
        <form id="configForm">
            <!-- General Settings -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-sliders me-2"></i> General Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="endpoint" class="form-label">API Endpoint</label>
                            <input type="text" class="form-control form-control-sm" id="endpoint" value="<?php echo siteUrl() ?>/api" />
                            <small class="text-muted">Your API endpoint URL</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cookie_name" class="form-label">Cookie Name</label>
                            <input type="text" class="form-control form-control-sm" id="cookie_name" value="ACTPRO_visitor">
                            <small class="text-muted">Name of the cookie for visitor tracking</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cookie_time" class="form-label">Cookie Expiration (days)</label>
                            <input type="number" class="form-control form-control-sm" id="cookie_time" value="7" min="1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Clean URL</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cleanUrl" checked>
                                <label class="form-check-label" for="cleanUrl">Remove tracking parameters</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Postback Settings -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-send me-2"></i> Postback Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="network" class="form-label">Network Name</label>
                            <input type="text" class="form-control form-control-sm" id="network" value="ACTPRO">
                            <small class="text-muted">Your CPA network name</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payout" class="form-label">Default Payout</label>
                            <input type="number" class="form-control form-control-sm" id="payout" value="0" step="0.01">
                            <small class="text-muted">Default payout amount for conversions</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offerwall Settings -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-wallet me-2"></i> Offerwall Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="trigger" class="form-label">Trigger Element</label>
                            <input type="text" class="form-control form-control-sm" id="trigger" value="#download-btn">
                            <small class="text-muted">CSS selector for offerwall trigger</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="required_conversion" class="form-label">Required Conversions</label>
                            <input type="number" class="form-control form-control-sm" id="required_conversion" value="2" min="1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="checkInEvery" class="form-label">Check Interval (seconds)</label>
                            <input type="number" class="form-control form-control-sm" id="checkInEvery" value="15" min="5">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Show Close Button</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showCloseButton" checked>
                                <label class="form-check-label" for="showCloseButton">Enable close button</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Offerwall Title</label>
                            <input type="text" class="form-control form-control-sm" id="title" value="Your File is Ready!">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="buttonText" class="form-label">Button Text</label>
                            <input type="text" class="form-control form-control-sm" id="buttonText" value="Download Now">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="text" class="form-label">Description Text</label>
                        <textarea class="form-control form-control-sm" id="text" rows="2">Complete 2 offers to unlock the download button.</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="buttonClick" class="form-label">Button Click Handler</label>
                        <textarea class="form-control form-control-sm" id="buttonClick" rows="3">function() {
// Start download when button is clicked
window.location.href = '/download-file';
}</textarea>
                        <small class="text-muted">JavaScript function to execute when button is clicked</small>
                    </div>
                    <div class="mb-3">
                        <label for="onComplete" class="form-label">On Complete Handler</label>
                        <textarea class="form-control form-control-sm" id="onComplete" rows="3">function() {
console.log('Offers completed!');
}</textarea>
                        <small class="text-muted">JavaScript function to execute when offers are completed</small>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                <button type="button" class="btn btn-primary me-md-2" id="generateBtn">
                    <i class="bi bi-gear me-2"></i> Generate Setup Code
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Generated Code -->
<div class="card mb-4" id="generatedCode" style="display: none;">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0"><i class="bi bi-code-square me-2"></i> Generated Setup Code</h4>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <h5 class="mb-3">ACTPRO Configuration</h5>
            <div class="position-relative bg-dark text-light p-3 rounded mb-3">
                <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn">Copy</button>
                <pre class="mb-0"><code id="setupCode"></code></pre>
            </div>
        </div>
        
        <div class="mb-4">
            <h5 class="mb-3">Implementation Example</h5>
            <div class="p-3 border rounded">
                <p>Include this in your HTML file:</p>
                <div class="position-relative bg-dark text-light p-3 rounded mb-3">
                    <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn">Copy</button>
                    <pre class="mb-0"><code id="htmlExample"></code></pre>
                </div>
                <p>Then initialize the tracking system:</p>
                <div class="position-relative bg-dark text-light p-3 rounded">
                    <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn">Copy</button>
                    <pre class="mb-0"><code id="initExample"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('configForm');
        const generateBtn = document.getElementById('generateBtn');
        const generatedCode = document.getElementById('generatedCode');
        const setupCode = document.getElementById('setupCode');
        const htmlExample = document.getElementById('htmlExample');
        const initExample = document.getElementById('initExample');

        // Generate setup code
        generateBtn.addEventListener('click', function() {
            // Get form values
            const config = {
                endpoint: document.getElementById('endpoint').value,
                cleanUrl: document.getElementById('cleanUrl').checked,
                cookie_name: document.getElementById('cookie_name').value,
                cookie_time: parseInt(document.getElementById('cookie_time').value),
                postback: {
                    network: document.getElementById('network').value,
                    payout: parseFloat(document.getElementById('payout').value)
                },
                offerwall: {
                    trigger: document.getElementById('trigger').value || null,
                    required_conversion: parseInt(document.getElementById('required_conversion').value),
                    checkInEvery: parseInt(document.getElementById('checkInEvery').value),
                    title: document.getElementById('title').value,
                    text: document.getElementById('text').value,
                    buttonText: document.getElementById('buttonText').value,
                    buttonClick: document.getElementById('buttonClick').value.trim() || null,
                    onComplete: document.getElementById('onComplete').value.trim() || null,
                    showCloseButton: document.getElementById('showCloseButton').checked
                }
            };

            // Generate the setup code
            let setupCodeText = 'ACTPRO.setup({\n';
            setupCodeText += `    endpoint: '${config.endpoint}',\n`;
            setupCodeText += `    cleanUrl: ${config.cleanUrl},\n`;
            setupCodeText += `    cookie_name: '${config.cookie_name}',\n`;
            setupCodeText += `    cookie_time: ${config.cookie_time},\n`;
            setupCodeText += '    postback: {\n';
            setupCodeText += `        network: '${config.postback.network}',\n`;
            setupCodeText += `        payout: ${config.postback.payout}\n`;
            setupCodeText += '    },\n';
            setupCodeText += '    offerwall: {\n';
            setupCodeText += `        trigger: ${config.offerwall.trigger ? `'${config.offerwall.trigger}'` : 'null'},\n`;
            setupCodeText += `        required_conversion: ${config.offerwall.required_conversion},\n`;
            setupCodeText += `        checkInEvery: ${config.offerwall.checkInEvery},\n`;
            setupCodeText += `        title: '${config.offerwall.title.replace(/'/g, "\\'")}',\n`;
            setupCodeText += `        text: '${config.offerwall.text.replace(/'/g, "\\'")}',\n`;
            setupCodeText += `        buttonText: '${config.offerwall.buttonText.replace(/'/g, "\\'")}',\n`;
            
            if (config.offerwall.buttonClick) {
                setupCodeText += `        buttonClick: ${config.offerwall.buttonClick},\n`;
            } else {
                setupCodeText += '        buttonClick: null,\n';
            }
            
            if (config.offerwall.onComplete) {
                setupCodeText += `        onComplete: ${config.offerwall.onComplete},\n`;
            } else {
                setupCodeText += '        onComplete: null,\n';
            }
            
            setupCodeText += `        showCloseButton: ${config.offerwall.showCloseButton}\n`;
            setupCodeText += '    }\n';
            setupCodeText += '});';

            // Generate examples
            const htmlExampleText = `<script src="actpro.js"><\/script>
<script>
${setupCodeText}
<\/script>`;

            const initExampleText = `// Initialize the tracking system
ACTPRO.init();`;

            // Display the generated code
            setupCode.textContent = setupCodeText;
            htmlExample.textContent = htmlExampleText;
            initExample.textContent = initExampleText;
            generatedCode.style.display = 'block';

            // Scroll to the generated code
            generatedCode.scrollIntoView({ behavior: 'smooth' });
        });

        // Copy button functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('copy-btn')) {
                const codeBlock = e.target.parentElement.querySelector('code');
                const textToCopy = codeBlock.textContent;
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Change button text temporarily
                    const originalText = e.target.textContent;
                    e.target.textContent = 'Copied!';
                    e.target.classList.remove('btn-outline-light');
                    e.target.classList.add('btn-success');
                    
                    setTimeout(() => {
                        e.target.textContent = originalText;
                        e.target.classList.remove('btn-success');
                        e.target.classList.add('btn-outline-light');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            }
        });
    });
</script>