/**
 * ACTPRO CPA Conversion Tracking system - Final Refined Version
 */

var ACTPRO = {

    config: {
        endpoint: 'http://localhost/actpro/api',
        cleanUrl: false,
        cookie_name: 'ACTPRO_visitor',
        cookie_time: 1,
        postback: {
            network: 'ACTPRO',
            payout: 0,
        },
        offerwall: {
            trigger: null, // CSS selector for trigger element
            required_conversion: 2,
            checkInEvery: 15,
            title: 'Your File is Ready!',
            text: 'Complete 2 offers to unlock the download button.', 
            buttonText: 'Download Now',
            buttonClick: null,
            onComplete: null,
            showCloseButton: true, // Option to enable close button
        }
    },

    params: {},

    /**
     * Update configuration
     */
    setup: function(config = {}) {
        // Modified deep merge function
        const deepMerge = (target, source) => {
            for (const key in source) {
                // Preserve functions by direct assignment
                if (typeof source[key] === 'function') {
                    target[key] = source[key];
                }
                // Handle regular objects
                else if (source[key] instanceof Object && !Array.isArray(source[key])) {
                    if (!target[key]) Object.assign(target, { [key]: {} });
                    deepMerge(target[key], source[key]);
                } 
                // Handle all other cases
                else {
                    target[key] = source[key];
                }
            }
            return target;
        };
        
        deepMerge(this.config, config);
    },

    /**
     * Process URL parameters and store in cookie
     */
    process_params: function() {
        // Initialize params with default values
        this.params = {
            source: 'direct',
            medium: 'direct',
            zone_id: 'direct',
            cost: 0
        };
    
        // Try to get from cookie first
        var cookieData = this.getCookie(this.config.cookie_name);
        if (cookieData) {
            try {
                var cookieParams = JSON.parse(cookieData);
                // Use cookie params if URL params don't exist
                for (var key in cookieParams) {
                    // Only use cookie values if they're not in URL params (except click_id and visitor_id)
                    if ((!new URLSearchParams(window.location.search).has(key) || 
                         key === 'click_id' || 
                         key === 'visitor_id') && 
                        cookieParams[key] !== undefined) {
                        this.params[key] = cookieParams[key];
                    }
                }
            } catch (e) {
                console.error('Failed to parse cookie data', e);
            }
        }
    
        // Process URL params
        var urlParams = new URLSearchParams(window.location.search);
        
        // Handle main tracking params
        if (urlParams.has('source')) this.params.source = urlParams.get('source');
        if (urlParams.has('medium')) this.params.medium = urlParams.get('medium');
        if (urlParams.has('zone_id')) this.params.zone_id = urlParams.get('zone_id');
        if (urlParams.has('cost')) this.params.cost = parseFloat(urlParams.get('cost')) || 0;
        
        // Handle special independent params (always update if present in URL)
        if (urlParams.has('click_id')) this.params.click_id = urlParams.get('click_id');
        if (urlParams.has('visitor_id')) this.params.visitor_id = urlParams.get('visitor_id');
    
        // Store in cookie (only if we have at least one non-default param or special params)
        const hasNonDefaultParams = this.params.source !== 'direct' || 
                                   this.params.medium !== 'direct' || 
                                   this.params.zone_id !== 'direct' || 
                                   this.params.cost !== 0 || 
                                   this.params.click_id || 
                                   this.params.visitor_id;
    
        if (hasNonDefaultParams) {
            this.cookie(this.config.cookie_name, JSON.stringify(this.params), this.config.cookie_time);
        }
    
        // Clean URL if enabled
        if (this.config.cleanUrl) {
            this.cleanUrl();
        }
    },

    /* Update Params */
    update_params: function(){
        this.cookie(this.config.cookie_name, JSON.stringify(this.params), this.config.cookie_time);
    },

    cleanUrl: function(){
        window.history.replaceState({}, document.title, window.location.pathname);
    },

    remove_param: function( paramToRemove ){
        // Get current URL without hash (to avoid issues)
        const url = new URL(window.location.href.split('#')[0]);
        // Remove the specified parameter
        url.searchParams.delete(paramToRemove);
        // Reconstruct the URL (including hash if it existed)
        let newUrl = url.toString();
        const hash = window.location.href.split('#')[1];
        if (hash) newUrl += `#${hash}`;
        // Update browser URL without reloading
        window.history.pushState({}, '', newUrl);
    },

    /**
     * AJAX helper function
     */
    ajax: function(options) {
        // Create XHR object
        const xhr = new XMLHttpRequest();
        
        // Set default method to GET if not specified
        const method = (options.method || 'GET').toUpperCase();
        
        // Process the URL (add query params for GET requests)
        let url = options.url;
        if (method === 'GET' && options.data) {
            const params = new URLSearchParams(options.data).toString();
            url += (url.includes('?') ? '&' : '?') + params;
        }
        
        // Open connection
        xhr.open(method, url, true);
        
        // Set headers
        if (options.headers) {
            for (const [key, value] of Object.entries(options.headers)) {
                xhr.setRequestHeader(key, value);
            }
        }

        xhr.setRequestHeader('X-Request-With', 'ACTPRO');
        xhr.setRequestHeader('X-Request-Nonce', Math.round( Math.random() * 999999 ));
        
        // Set content type for POST/PUT if not specified
        if (['POST', 'PUT', 'PATCH'].includes(method) && options.data && 
            !(options.headers && options.headers['Content-Type'])) {
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        
        // Handle response
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Success
                let response;
                try {
                    response = xhr.responseText;
                    // Try to parse JSON if response is JSON
                    if (xhr.getResponseHeader('Content-Type')?.includes('application/json')) {
                        response = JSON.parse(response);
                    }
                } catch (e) {
                    // Response wasn't JSON or parsing failed
                }
                
                if (options.success) {
                    options.success(response, xhr.status, xhr);
                }
            } else {
                // Error
                if (options.error) {
                    options.error(xhr, xhr.status, xhr.statusText);
                }
            }
            
            // Complete callback
            if (options.complete) {
                options.complete(xhr, xhr.status);
            }
        };
        
        // Handle network errors
        xhr.onerror = function() {
            if (options.error) {
                options.error(xhr, xhr.status, 'Network error');
            }
            if (options.complete) {
                options.complete(xhr, xhr.status);
            }
        };
    
        // Send request
        let dataToSend = null;
        if (['POST', 'PUT', 'PATCH'].includes(method) && options.data) {
            if (options.data instanceof FormData || 
                options.data instanceof URLSearchParams || 
                typeof options.data === 'string') {
                dataToSend = options.data;
            } else {
                // Convert object to URL-encoded string by default
                dataToSend = new URLSearchParams(options.data).toString();
            }
        }
        
        xhr.send(dataToSend);
    },

    /**
     * Cookie management
     */
    cookie: function(cookieName, cookieValue, expiry, path = '/', domain = false) {
        expiry = expiry || 1;
        path = path || '/';
        var expires = '';
        if (expiry) {
            var date = new Date();
            date.setTime(date.getTime() + (expiry * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        
        var domainPart = domain ? '; domain=' + domain : '';
        document.cookie = cookieName + '=' + encodeURIComponent(cookieValue) + expires + '; path=' + path + domainPart;
    },

    /**
     * Get cookie value
     */
    getCookie: function(name) {
        var nameEQ = name + '=';
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf(nameEQ) === 0) {
                return decodeURIComponent(cookie.substring(nameEQ.length));
            }
        }
        return null;
    },

    /**
     * Register visitor
     */
    register: function(callback){
        // Register visitor if needed
        if (this.params.visitor_id) {
            return false;
        }

        var requiredParams = {
            source: this.params.source,
            medium: this.params.medium,
            zone_id: this.params.zone_id,
            cost: this.params.cost
        };
        
        this.ajax({
            method: 'POST',
            url: this.config.endpoint + '/register',
            data: requiredParams,
            success: function(response) {
                if (response.status === 'success' && response.visitor_id) {
                    // Update params and cookie with visitor_id
                    ACTPRO.params.visitor_id = response.visitor_id;
                    ACTPRO.update_params();
                    
                    if (callback) callback(response);
                }
            }
        });
    },

    /**
     * Confirm if visitor completed offers for get the code.
     * when user confirmed they will get an code from server display in popup.
     * example: title: You have completed all offers. message: you code is 123456.
     */
    confirm: function(title = 'Your Confirmation Code', message = 'Your confirmation code is: '){
        // Register visitor if needed
        if (!this.params.visitor_id) {
            this.popup( 'Visitor ID not found!', 'Visitor ID was not found. Please complete the offers and then try again.' );
            return false;
        }

        var requiredParams = {
            visitor_id: this.params.visitor_id,
            required_conversion: this.config.offerwall.required_conversion,
        };
        
        this.ajax({
            method: 'POST',
            url: this.config.endpoint + '/confirm',
            data: requiredParams,
            headers: {
                'X-Request-ID': this.params.visitor_id
            },
            success: function(res) {
                if (res.status === 'success' && res.visitor_id && res.code) {
                    ACTPRO.popup( title, message+'<b>'+res.code+'</b>' );
                }else if( res.status === 'error' && res.title && res.message ){
                    ACTPRO.popup( res.title, res.message );
                }else if( res.status === 'invalid' ){
                    ACTPRO.popup( res.title, res.message );
                }else{
                    ACTPRO.popup( 'Something went wrong!', 'please check that you have completed all number of required offers.' );
                }
            },
            error: function(status, error){
                ACTPRO.popup( 'Error: '+status, error );
            }
        });
    },

    /**
     * Send postback
    */
    px: function(callback) {
        if (!this.params.click_id) {
            if (callback) callback({status: 'error', message: 'click_id is required'});
            return;
        }
        
        var postbackData = {
            click_id: this.params.click_id,
            network: this.config.postback.network,
            payout: this.config.postback.payout
        };
                
        this.ajax({
            method: 'POST',
            url: this.config.endpoint + '/px',
            data: postbackData,
            headers: {
                'X-Request-ID': this.params.click_id
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Remove click_id from params after successful postback
                    delete ACTPRO.params.click_id;
                    ACTPRO.remove_param( 'click_id' );
                    // Update cookie if cookie handling is available
                    ACTPRO.update_params();
                    
                }else if( response.status === 'error' && response.click_id === false ){
                    // Remove click_id from params after successful postback
                    delete ACTPRO.params.click_id;
                    ACTPRO.remove_param( 'click_id' );
                    // Update cookie if cookie handling is available
                    ACTPRO.update_params();
                }
                if (callback) callback(response);
            },
            error: function(status, error) {
                if (callback) callback({status: 'error', message: error});
            }
        });
    },

    /**
     * Offerwall system (popup version)
     */
    offerwall: function() {
        if (!this.params.visitor_id) {
            this.register();
        }
        this.config.offerwall.render = false;
        
        // Create popup container
        const popup = document.createElement('div');
        popup.id = 'actpro-offerwall';
        popup.className = 'actpro-offerwall-container';
        popup.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
        
        // Create offerwall content
        const offerwallContent = document.createElement('div');
        offerwallContent.className = 'bg-white rounded-4 shadow-lg';
        offerwallContent.style.cssText = `
            width: 95%;
            max-width: 600px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        `;
        
        // Build offerwall HTML
        offerwallContent.innerHTML = `
            <div class="p-4 bg-primary text-white">
                <h3 class="mb-1 fw-bold fs-3 offerwall-title">${this.config.offerwall.title}</h3>
                <p class="mb-0 fs-6 offerwall-text">${this.config.offerwall.text}</p>
                ${this.config.offerwall.showCloseButton ? 
                    '<button class="actpro-close-btn btn btn-sm position-absolute top-0 end-0 m-2 bg-white text-primary rounded-circle border-0"><i class="bi bi-x-lg"></i></button>' : 
                    ''
                }
            </div>
            <div class="py-4 px-3 bg-light" style="overflow-y: auto">
                <div id="actpro-offers-container">
                    <div class="d-flex justify-content-center align-items-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top" style="height: 100px;padding: 10px 30px">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <span id="actpro-status-text" class="text-muted">
                        <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
                        Checking offers...
                    </span>
                </div>
                <button id="actpro-download-btn" class="btn btn-primary w-100 py-2 fw-bold offerwall-download-btn" disabled>
                    ${this.config.offerwall.buttonText}
                </button>
            </div>
        `;
        
        popup.appendChild(offerwallContent);
        document.body.appendChild(popup);
        this.config.offerwall.render = true;
        
        // Set up event listeners
        document.getElementById('actpro-download-btn').addEventListener('click', () => {
            if (this.config.offerwall.buttonClick) {
                this.config.offerwall.buttonClick();
            }
        });
        
        if (this.config.offerwall.showCloseButton) {
            document.querySelector('.actpro-close-btn').addEventListener('click', () => {
                popup.remove();
                this.config.offerwall.render = false;
            });
        }
        
        // Load offers
        this.loadOffers();
    },
    
    loadOffers: function() {
        this.ajax({
            method: 'POST',
            url: `${this.config.endpoint}/getOffers`,
            data: { visitor_id: this.params.visitor_id },
            headers: {
                'X-Request-ID': this.params.visitor_id
            },
            success: (response) => {
                if (response.status === 'success') {
                    this.renderOffers(response.offers);
                    this.updateStatus(response.total_conversion);
                    
                    // Check conversions periodically if not completed
                    if (response.total_conversion < this.config.offerwall.required_conversion) {
                        var loadOfferTime = setInterval(() => {
                            clearInterval( loadOfferTime );
                            if( this.config.offerwall.render ){
                                this.loadOffers()
                            }
                        }, this.config.offerwall.checkInEvery * 1000);
                    }
                }else if( response.status === 'error' ){
                    if( response.visitor_id === false ){
                        delete this.params.visitor_id;
                        this.register();
                        var wallTime = setInterval( () => {
                            clearInterval(wallTime);
                            this.loadOffers();
                        }, 1000);
                    }
                }else if( response.status === 'invalid' ){
                    const container = document.getElementById('actpro-offers-container');
                    if (container) {
                        container.innerHTML = `
                            <div class="alert alert-danger text-center">
                                Failed to load offers. Please try again later.
                            </div>
                        `;
                    }
                }
            },
            error: () => {
                const container = document.getElementById('actpro-offers-container');
                if (container) {
                    container.innerHTML = `
                        <div class="alert alert-danger text-center">
                            Failed to load offers. Please try again later.
                        </div>
                    `;
                }
            }
        });
    },
    
    renderOffers: function(offers) {
        const container = document.getElementById('actpro-offers-container');
        if (!container || !offers) return;
        container.innerHTML = offers.length ? '' : `
            <div class="alert alert-info text-center">
                No offers available at this time.
            </div>
        `;
        
        offers.forEach(offer => {
            const offerEl = document.createElement('div');
            offerEl.className = `card border d-block rounded-3 mb-3`;

            var completedCss = `
                background: #eef8ea;
                border-color: #bce5ab !important;
            `;

            var clickedCss = `
                background: #ebeefa;
                border-color: #b0bded !important;
            `;

            offerEl.style.cssText = offer.is_converted ? completedCss : (offer.is_clicked ? clickedCss : '');
                
            offerEl.innerHTML = `
                <div class="card-body p-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi ${offer.icon || 'bi-gift'} fs-4 ${offer.is_converted ? 'text-success':'text-primary'}"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1 fw-bold ${offer.is_converted ? 'text-success':(offer.is_clicked ? 'text-primary':'')}">${offer.title}</h6>
                            <p class="card-text small pb-0 mb-1">${offer.description}</p>
                    ${offer.time ? `<span class="badge bg-light text-dark me-1 fw-normal"><i class="bi bi-clock me-2"></i>${offer.time}</span>` : ''}
                    ${offer.category ? `<span class="badge bg-light text-dark fw-normal">${offer.category}</span>` : ''}
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                ${offer.completed ? '<small class="text-muted fs-small"><i class="bi bi-check-circle-fill text-success"></i> '+offer.completed+' completed.<small>':''}
                            </div>
                            ${offer.is_converted ? `
                                <button class="btn btn-success btn-sm rounded-pill px-3" style="font-size:12px;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Done
                                </button>
                            ` : `
                                <a href="${offer.tracking_link}" class="btn btn-primary btn-sm rounded-pill d-block px-3" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i> Open</a>
                            `}
                        </div>
                        ${offer.is_clicked && !offer.is_converted ? `<small class="d-block" style="font-size:12px;">Please complete the offer you started.</small>`:''}
                    </div>
                </div>
            `;
            container.appendChild(offerEl);
        });
    },
    
    updateStatus: function(completed) {
        const required = this.config.offerwall.required_conversion;
        const status = document.getElementById('actpro-status-text');
        const btn = document.getElementById('actpro-download-btn');
        
        if (status) {
            status.innerHTML = completed >= required ? `
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                ${completed} Offers completed
            ` : `
                <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
                Completed ${completed} of ${required} offers
            `;
        }
        
        if (btn) {
            btn.disabled = completed < required;
        }
        
        if (completed >= required && this.config.offerwall.onComplete) {
            this.config.offerwall.onComplete();
        }
    },

    popup: function(title, message) {
        // Create the main popup container
        const popupDiv = document.createElement('div');
        popupDiv.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
        popupDiv.style.backgroundColor = 'rgba(0,0,0,0.5)';
        popupDiv.style.zIndex = '9999999';
        
        // Create the popup content container
        const contentDiv = document.createElement('div');
        contentDiv.className = 'bg-white rounded shadow p-4 position-relative';
        contentDiv.style.maxWidth = '500px';
        contentDiv.style.width = '90%';
        
        // Create the close button
        const closeButton = document.createElement('button');
        closeButton.className = 'btn-close position-absolute top-0 end-0 m-2';
        closeButton.setAttribute('aria-label', 'Close');
        
        // Create the title element
        const titleElement = document.createElement('h5');
        titleElement.className = 'fw-bold mb-3';
        titleElement.innerHTML = title;
        
        // Create the message element
        const messageElement = document.createElement('div');
        messageElement.className = 'mb-3';
        messageElement.innerHTML = message;
        
        // Add a Bootstrap icon (using Bootstrap Icons)
        const icon = document.createElement('i');
        icon.className = 'bi bi-info-circle-fill text-primary me-2';
        
        // Prepend the icon to the title
        titleElement.insertBefore(icon, titleElement.firstChild);
        
        // Assemble the popup
        contentDiv.appendChild(closeButton);
        contentDiv.appendChild(titleElement);
        contentDiv.appendChild(messageElement);
        popupDiv.appendChild(contentDiv);
        
        // Add to document body
        document.body.appendChild(popupDiv);
        
        // Close functionality
        closeButton.addEventListener('click', function() {
            document.body.removeChild(popupDiv);
        });
    },

    /**
     * Initialize the tracking system
     */
    init: function() {
        // Process parameters (checks cookie first, then URL, then defaults)
        this.process_params();
        
        // Set up offerwall trigger if configured
        if (this.config.offerwall.trigger) {
            var trigger = document.querySelector(this.config.offerwall.trigger);
            if (trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    ACTPRO.offerwall();
                });
            }
        }
    }
};