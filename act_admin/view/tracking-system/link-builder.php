<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h2 class="h4 mb-0"><i class="bi bi-link-45deg me-2"></i>Campaign Link Builder</h2>
    </div>
    <div class="card-body">
        <form id="linkBuilderForm">
            <div class="mb-3">
                <label for="destinationUrl" class="form-label">Destination URL</label>
                <input type="url" class="form-control form-control-sm" id="destinationUrl" placeholder="https://example.com/offer" required>
                <div class="form-text">Enter the final URL you want to send traffic to</div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="sourceParam" class="form-label">Source Parameter (Macro)</label>
                    <input type="text" class="form-control form-control-sm" id="sourceParam" placeholder="{source}">
                    <div class="form-text">e.g. {source}, {traffic_source}, etc.</div>
                </div>
                
                <div class="col-md-6">
                    <label for="mediumParam" class="form-label">Medium Parameter (Macro)</label>
                    <input type="text" class="form-control form-control-sm" id="mediumParam" placeholder="{medium}">
                    <div class="form-text">e.g. {medium}, {placement}, etc.</div>
                </div>
                
                <div class="col-md-6">
                    <label for="zoneIdParam" class="form-label">Zone ID Parameter (Macro)</label>
                    <input type="text" class="form-control form-control-sm" id="zoneIdParam" placeholder="{zone_id}">
                    <div class="form-text">e.g. {zone_id}, {sub_id}, etc.</div>
                </div>
                
                <div class="col-md-6">
                    <label for="costParam" class="form-label">Cost Parameter (Macro)</label>
                    <input type="text" class="form-control form-control-sm" id="costParam" placeholder="{cost}">
                    <div class="form-text">e.g. {cost}, {price}, etc.</div>
                </div>
            </div>
        </form>
        
        <hr class="my-4">
        
        <div class="mb-3">
            <label class="form-label">Generated Tracking Link</label>
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" id="generatedLink" readonly>
                <button class="btn btn-primary" id="copyButton" type="button"><i class="bi bi-clipboard me-1"></i> Copy</button>
            </div>
            <div class="form-text">This link will update automatically as you type</div>
        </div>
        
        <div class="alert alert-info mt-4">
            <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Example Output</h5>
            <p class="mb-1">With parameters filled, your link will look like:</p>
            <code id="exampleOutput">https://track.actprocpa.com/?dest=https://example.com/offer&source={source}&medium={medium}&zone_id={zone_id}&cost={cost}</code>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Cache DOM elements
        const elements = {
            form: document.getElementById('linkBuilderForm'),
            generatedLink: document.getElementById('generatedLink'),
            copyButton: document.getElementById('copyButton'),
            exampleOutput: document.getElementById('exampleOutput'),
            inputs: {
                destinationUrl: document.getElementById('destinationUrl'),
                sourceParam: document.getElementById('sourceParam'),
                mediumParam: document.getElementById('mediumParam'),
                zoneIdParam: document.getElementById('zoneIdParam'),
                costParam: document.getElementById('costParam')
            }
        };

        // Constants
        const DEFAULT_EXAMPLE = 'https://example.com/offer?source={source}&medium={medium}&zone_id={zone_id}&cost={cost}';

        // Debounce function to optimize performance
        const debounce = (func, delay = 300) => {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        };

        // Update link without encoding parameters
        const updateLink = debounce(() => {
            const destUrl = elements.inputs.destinationUrl.value.trim();
            if (!destUrl) {
                elements.generatedLink.value = '';
                elements.exampleOutput.textContent = DEFAULT_EXAMPLE;
                return;
            }

            try {
                // Split URL into base and query parts
                const [baseUrl, existingQuery] = destUrl.split('?');
                const params = new Map();
                
                // Add existing parameters
                if (existingQuery) {
                    existingQuery.split('&').forEach(pair => {
                        const [key, value] = pair.split('=');
                        if (key) params.set(key, value || '');
                    });
                }

                // Add/update tracking parameters (raw values, no encoding)
                const trackingParams = {
                    source: elements.inputs.sourceParam.value.trim(),
                    medium: elements.inputs.mediumParam.value.trim(),
                    zone_id: elements.inputs.zoneIdParam.value.trim(),
                    cost: elements.inputs.costParam.value.trim()
                };

                Object.entries(trackingParams).forEach(([key, value]) => {
                    if (value) params.set(key, value);
                });

                // Build query string without encoding
                const queryString = Array.from(params.entries())
                    .map(([key, value]) => `${key}=${value}`)
                    .join('&');

                // Rebuild final URL
                const finalUrl = queryString ? `${baseUrl}?${queryString}` : baseUrl;
                
                elements.generatedLink.value = finalUrl;
                elements.exampleOutput.textContent = finalUrl || DEFAULT_EXAMPLE;
            } catch (e) {
                console.error('Error building URL:', e);
                elements.generatedLink.value = '';
                elements.exampleOutput.textContent = DEFAULT_EXAMPLE;
            }
        });

        // Modern clipboard API with fallback
        const copyToClipboard = () => {
            if (!elements.generatedLink.value) return;

            if (navigator.clipboard) {
                navigator.clipboard.writeText(elements.generatedLink.value)
                    .then(() => showCopyFeedback())
                    .catch(err => console.error('Failed to copy:', err));
            } else {
                // Fallback for older browsers
                elements.generatedLink.select();
                document.execCommand('copy');
                showCopyFeedback();
            }
        };

        // Visual feedback for copy action
        const showCopyFeedback = () => {
            const originalHTML = elements.copyButton.innerHTML;
            elements.copyButton.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
            elements.copyButton.disabled = true;
            
            setTimeout(() => {
                elements.copyButton.innerHTML = originalHTML;
                elements.copyButton.disabled = false;
            }, 2000);
        };

        // Event listeners
        elements.form.addEventListener('input', updateLink);
        elements.copyButton.addEventListener('click', copyToClipboard);

        // Initial update
        updateLink();
    });
</script>
