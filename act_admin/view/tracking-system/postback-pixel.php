<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h2 class="h4 mb-0"><i class="bi bi-code-square me-2"></i>Pixel Link Generator</h2>
    </div>
    <div class="card-body">
        <form id="pixelForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="clickId" class="form-label">Click ID Parameter</label>
                    <input type="text" class="form-control form-control-sm" id="clickId" placeholder="{click_id}">
                    <div class="form-text">Macro or value for click tracking</div>
                </div>
                
                <div class="col-md-4">
                    <label for="network" class="form-label">Network Parameter</label>
                    <input type="text" class="form-control form-control-sm" id="network" placeholder="{network}">
                    <div class="form-text">Network name or identifier</div>
                </div>
                
                <div class="col-md-4">
                    <label for="payout" class="form-label">Payout Parameter</label>
                    <input type="text" class="form-control form-control-sm" id="payout" placeholder="{payout}">
                    <div class="form-text">Payout amount or macro</div>
                </div>
            </div>
            
            <div class="mt-4">
                <h5 class="mb-3"><i class="bi bi-link-45deg me-2"></i>Generated Pixel Link</h5>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="pixelLink" readonly>
                    <button class="btn btn-primary" id="copyPixelBtn" type="button">
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                </div>
            </div>
            
            <div class="mt-4">
                <h5 class="mb-3"><i class="bi bi-eye me-2"></i>Pixel Preview</h5>
                <div class="pixel-preview bg-light p-3 rounded-3">
                    <div id="pixelPreview"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Elements
        const form = document.getElementById('pixelForm');
        const pixelLink = document.getElementById('pixelLink');
        const copyPixelBtn = document.getElementById('copyPixelBtn');
        const pixelPreview = document.getElementById('pixelPreview');
        const testPixelBtn = document.getElementById('testPixelBtn');
        
        // Input fields
        const clickIdInput = document.getElementById('clickId');
        const networkInput = document.getElementById('network');
        const payoutInput = document.getElementById('payout');
        
        // Base URL
        const BASE_URL = '<?php echo siteUrl() ?>/api/airpx-s2s';
        
        // Update pixel link in real-time
        form.addEventListener('input', updatePixelLink);
        
        // Copy button functionality
        copyPixelBtn.addEventListener('click', () => {
            pixelLink.select();
            document.execCommand('copy');
            showFeedback(copyPixelBtn, 'Copied!');
        });
        
        // Update the pixel link
        function updatePixelLink() {
            const params = [];
            
            // Add parameters if they have values
            if (clickIdInput.value.trim()) params.push(`click_id=${clickIdInput.value.trim()}`);
            if (networkInput.value.trim()) params.push(`network=${networkInput.value.trim()}`);
            if (payoutInput.value.trim()) params.push(`payout=${payoutInput.value.trim()}`);
            
            // Build the final URL
            const finalUrl = params.length ? `${BASE_URL}?${params.join('&')}` : BASE_URL;
            
            // Update outputs
            pixelLink.value = finalUrl;
            pixelPreview.innerHTML = params.length 
                ? `<strong>URL:</strong> ${finalUrl}` : 'Enter parameters to generate pixel URL';
        }
        
        // Show feedback on buttons
        function showFeedback(button, message) {
            const originalHtml = button.innerHTML;
            button.innerHTML = `<i class="bi bi-check2 me-1"></i> ${message}`;
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
            }, 2000);
        }
        
        // Initial update
        updatePixelLink();
    });
</script>