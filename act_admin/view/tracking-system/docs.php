<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<style>
	/* Improved code block styling */
	.code-container {
		position: relative;
		margin: 1.5rem 0;
		border-radius: 6px;
		overflow: hidden;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
	}
	.code-header {
		background: #2d2d2d;
		color: #fff;
		padding: 8px 15px;
		font-family: monospace;
		font-size: 0.9rem;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.code-body {
		background: #1e1e1e;
		color: #d4d4d4;
		padding: 15px;
		overflow-x: auto;
		font-family: 'Courier New', Courier, monospace;
		font-size: 0.95rem;
		line-height: 1.5;
		tab-size: 4;
	}
	.code-body code {
		display: block;
		white-space: pre;
	}
	.copy-btn {
		background: #3e3e3e;
		border: none;
		color: #fff;
		padding: 3px 8px;
		border-radius: 4px;
		font-size: 0.8rem;
		cursor: pointer;
		transition: all 0.2s;
	}
	.copy-btn:hover {
		background: #4e4e4e;
	}
	.copy-btn.copied {
		background: #28a745;
	}
	
	/* In-page navigation */
	.page-nav {
		background: var(--bs-tertiary-bg);
		padding: 1.5rem;
		border-radius: 8px;
		margin-bottom: 2rem;
	}
	.page-nav h4 {
		margin-bottom: 1rem;
		padding-bottom: 0.5rem;
		border-bottom: 1px solid #dee2e6;
	}
	.page-nav ul {
		list-style: none;
		padding-left: 0;
		margin-bottom: 0;
	}
	.page-nav li {
		margin-bottom: 0.5rem;
	}
	.page-nav a {
		color: var(--bs-tertiary-color);
		text-decoration: none;
		display: flex;
		align-items: center;
		padding: 5px 0;
		transition: all 0.2s;
	}
	.page-nav a:hover {
		color: #0d6efd;
	}
	.page-nav a i {
		margin-right: 8px;
		font-size: 0.9rem;
	}
	
	/* Section styling */
	.doc-section {
		margin-bottom: 3rem;
		padding-bottom: 2rem;
		border-bottom: 1px solid #eee;
	}
	.doc-section:last-child {
		border-bottom: none;
	}
	
	/* Table styling */
	.config-table {
		margin: 1.5rem 0;
	}
	.config-table th {
		background: var(--bs-tertiary-bg);
		color: var(--bs-tertiary-color);
	}
	
	/* Alert styling */
	.alert-note {
		border-left: 4px solid #0d6efd;
	}
</style>
<div class="row justify-content-center w-100">
	<div class="col-12 w-100">
		<!-- In-page navigation -->
		<div class="page-nav">
			<h4><i class="bi bi-list"></i> Documentation Navigation</h4>
			<ul>
				<li><a href="#overview"><i class="bi bi-info-circle"></i> Overview</a></li>
				<li><a href="#installation"><i class="bi bi-download"></i> Installation</a></li>
				<li><a href="#configuration"><i class="bi bi-gear"></i> Configuration</a></li>
				<li><a href="#basic-usage"><i class="bi bi-play-circle"></i> Basic Usage</a></li>
				<li><a href="#offerwall-system"><i class="bi bi-wallet"></i> Offerwall System</a></li>
				<li><a href="#methods-reference"><i class="bi bi-code-square"></i> Methods Reference</a></li>
				<li><a href="#examples"><i class="bi bi-file-earmark-code"></i> Examples</a></li>
				<li><a href="#troubleshooting"><i class="bi bi-question-circle"></i> Troubleshooting</a></li>
			</ul>
		</div>
		
		<!-- Overview -->
		<div class="doc-section" id="overview">
			<h2 class="mb-4"><i class="bi bi-info-circle me-2"></i> Overview</h2>
			<p>The ACTPRO CPA Conversion Tracking system is a JavaScript solution designed to help you track conversions, manage offer walls, and handle postbacks for CPA (Cost Per Action) marketing campaigns.</p>
			
			<div class="alert alert-primary alert-note">
				<h5 class="alert-heading">Key Features</h5>
				<ul class="mb-0">
					<li>Visitor tracking with cookie persistence</li>
					<li>URL parameter processing for campaign tracking</li>
					<li>Offerwall system with conversion requirements</li>
					<li>Postback handling for CPA networks</li>
					<li>Simple API integration</li>
					<li>Customizable configuration</li>
				</ul>
			</div>
		</div>

		<!-- Installation -->
		<div class="doc-section" id="installation">
			<h2 class="mb-4"><i class="bi bi-download me-2"></i> Installation</h2>
			<p>To use the ACTPRO tracking system, simply include the JavaScript file in your HTML:</p>
			
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;script src="/path/to/actpro.min.js"&gt;&lt;/script&gt;</code>
				</div>
			</div>
			
			<p>Alternatively, you can include it directly from a CDN (if available):</p>
			
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;script src="https://airaju94.github.io/cdn/actpro.min.js?v=<?php echo uniqid() ?>"&gt;&lt;/script&gt;</code>
				</div>
			</div>

			<p>Here is ACTPRO Obfuscator Version</p>
			
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;script src="https://airaju94.github.io/cdn/actpro-obf.js?v=<?php echo uniqid() ?>"&gt;&lt;/script&gt;</code>
				</div>
			</div>
			
			<div class="alert alert-info alert-note">
				<i class="bi bi-info-circle me-2"></i> The script should be loaded in the <code>&lt;head&gt;</code> section or before the closing <code>&lt;body&gt;</code> tag.
			</div>
		</div>

		<!-- Configuration -->
		<div class="doc-section" id="configuration">
			<h2 class="mb-4"><i class="bi bi-gear me-2"></i> Configuration</h2>
			<p>The system comes with default configuration that can be customized before initialization:</p>
			
			<div class="code-container">
				<div class="code-header">
					<span>JavaScript</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>ACTPRO.setup({
endpoint: 'https://your-domain.com/api', // Your API endpoint
cleanUrl: true, // Remove tracking parameters from URL after processing
cookie_name: 'ACTPRO_visitor', // Cookie name for visitor tracking
cookie_time: 7, // Cookie expiration in days

// Postback configuration
postback: {
network: 'ACTPRO', // Your CPA network name
payout: 0 // Default payout amount
},

// Offerwall configuration
offerwall: {
trigger: '#download-btn', // CSS selector for offerwall trigger
required_conversion: 2, // Number of offers required to complete
checkInEvery: 15, // Check for conversions every X seconds
title: 'Your File is Ready!', // Offerwall title
text: 'Complete 2 offers to unlock the download button.', // Description text
buttonText: 'Download Now', // Button text
showCloseButton: true // Show close button on offerwall
}
});</code>
				</div>
			</div>
			
			<h4 class="mt-4">Configuration Options</h4>
			<table class="table config-table">
				<thead>
					<tr>
						<th>Option</th>
						<th>Description</th>
						<th>Default</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>endpoint</code></td>
						<td>Your API endpoint URL</td>
						<td>'http://localhost/actpro/api'</td>
					</tr>
					<tr>
						<td><code>cleanUrl</code></td>
						<td>Remove tracking parameters from URL after processing</td>
						<td>false</td>
					</tr>
					<tr>
						<td><code>cookie_name</code></td>
						<td>Name of the cookie used to store visitor data</td>
						<td>'ACTPRO_visitor'</td>
					</tr>
					<tr>
						<td><code>cookie_time</code></td>
						<td>Cookie expiration time in days</td>
						<td>1</td>
					</tr>
					<tr>
						<td><code>postback.network</code></td>
						<td>Name of your CPA network</td>
						<td>'ACTPRO'</td>
					</tr>
					<tr>
						<td><code>postback.payout</code></td>
						<td>Default payout amount for conversions</td>
						<td>0</td>
					</tr>
					<tr>
						<td><code>offerwall.trigger</code></td>
						<td>CSS selector for offerwall trigger element</td>
						<td>null</td>
					</tr>
					<tr>
						<td><code>offerwall.required_conversion</code></td>
						<td>Number of offers required to complete</td>
						<td>2</td>
					</tr>
					<tr>
						<td><code>offerwall.checkInEvery</code></td>
						<td>Interval (in seconds) to check for completed offers</td>
						<td>15</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Basic Usage -->
		<div class="doc-section" id="basic-usage">
			<h2 class="mb-4"><i class="bi bi-play-circle me-2"></i> Basic Usage</h2>
			
			<h4 class="mt-4">1. Initialization</h4>
			<p>Initialize the tracking system after configuring it:</p>
			<div class="code-container">
				<div class="code-header">
					<span>JavaScript</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>// Configure first (optional if using defaults)
ACTPRO.setup({
endpoint: 'https://your-domain.com/api',
cleanUrl: true
});

// Initialize the tracking system
ACTPRO.init();</code>
				</div>
			</div>

			<h4 class="mt-4">2. Tracking Parameters</h4>
			<p>The system automatically processes these URL parameters:</p>
			<ul>
				<li><code>source</code> - Traffic source (e.g., facebook, google)</li>
				<li><code>medium</code> - Traffic medium (e.g., cpc, banner)</li>
				<li><code>zone_id</code> - Zone/placement ID</li>
				<li><code>cost</code> - Cost per click/impression</li>
				<li><code>click_id</code> - Click ID from CPA network</li>
				<li><code>visitor_id</code> - Existing visitor ID</li>
			</ul>
			
			<p>Example URL with tracking parameters:</p>
			<div class="code-container">
				<div class="code-header">
					<span>URL Example</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>https://your-site.com/?source=facebook&medium=cpc&zone_id=123&click_id=abc123</code>
				</div>
			</div>
			
			<h4 class="mt-4">3. Sending Postbacks</h4>
			<p>To send a conversion postback to your CPA network:</p>
			<div class="code-container">
				<div class="code-header">
					<span>JavaScript</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>ACTPRO.px(function(response) {
if (response.status === 'success') {
console.log('Postback sent successfully');
}
});</code>
				</div>
			</div>
		</div>

		<!-- Offerwall System -->
		<div class="doc-section" id="offerwall-system">
			<h2 class="mb-4"><i class="bi bi-wallet me-2"></i> Offerwall System</h2>
			
			<p>The offerwall system allows you to require users to complete CPA offers before accessing content.</p>
			
			<h4 class="mt-4">Basic Setup</h4>
			<p>Configure the offerwall in your setup:</p>
			<div class="code-container">
				<div class="code-header">
					<span>JavaScript</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>ACTPRO.setup({
offerwall: {
trigger: '#download-btn',
required_conversion: 2,
title: 'Almost There!',
text: 'Complete 2 offers to download your file.',
buttonText: 'Download Now',
buttonClick: function() {
	// Start download when button is clicked
	window.location.href = '/download-file';
},
onComplete: function() {
	// This runs when required offers are completed
	console.log('Offers completed!');
}
}
});</code>
				</div>
			</div>

			<h4 class="mt-4">Manual Trigger</h4>
			<p>You can manually trigger the offerwall:</p>
			<div class="code-container">
				<div class="code-header">
					<span>JavaScript</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>// Show the offerwall
ACTPRO.offerwall();

// Check if user completed offers
ACTPRO.confirm('Your Code', 'Your confirmation code is: ');</code>
				</div>
			</div>
			
			<h4 class="mt-4">Offerwall Flow</h4>
			<ol>
				<li>User clicks trigger element (or offerwall is manually triggered)</li>
				<li>System checks if visitor is registered (creates new visitor if needed)</li>
				<li>Offerwall loads available offers from your API</li>
				<li>User completes offers (system checks periodically)</li>
				<li>When requirements are met, the download button is enabled</li>
				<li>Optional callback functions are executed</li>
			</ol>
		</div>

		<!-- Methods Reference -->
		<div class="doc-section" id="methods-reference">
			<h2 class="mb-4"><i class="bi bi-code-square me-2"></i> Methods Reference</h2>
			
			<h4 class="mt-4">Core Methods</h4>
			<table class="table config-table">
				<thead>
					<tr>
						<th>Method</th>
						<th>Description</th>
						<th>Parameters</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>setup(config)</code></td>
						<td>Update system configuration</td>
						<td><code>config</code> - Configuration object</td>
					</tr>
					<tr>
						<td><code>init()</code></td>
						<td>Initialize the tracking system</td>
						<td>None</td>
					</tr>
					<tr>
						<td><code>process_params()</code></td>
						<td>Process URL parameters and store in cookie</td>
						<td>None</td>
					</tr>
					<tr>
						<td><code>register(callback)</code></td>
						<td>Register a new visitor with the API</td>
						<td><code>callback</code> - Optional callback function</td>
					</tr>
				</tbody>
			</table>
			
			<h4 class="mt-4">Offerwall Methods</h4>
			<table class="table config-table">
				<thead>
					<tr>
						<th>Method</th>
						<th>Description</th>
						<th>Parameters</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>offerwall()</code></td>
						<td>Show the offerwall popup</td>
						<td>None</td>
					</tr>
					<tr>
						<td><code>loadOffers()</code></td>
						<td>Load offers from API</td>
						<td>None</td>
					</tr>
					<tr>
						<td><code>confirm(title, message)</code></td>
						<td>Confirm offer completion and get code</td>
						<td>
							<code>title</code> - Popup title<br>
							<code>message</code> - Popup message
						</td>
					</tr>
				</tbody>
			</table>
			
			<h4 class="mt-4">Utility Methods</h4>
			<table class="table config-table">
				<thead>
					<tr>
						<th>Method</th>
						<th>Description</th>
						<th>Parameters</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>px(callback)</code></td>
						<td>Send postback to CPA network</td>
						<td><code>callback</code> - Optional callback function</td>
					</tr>
					<tr>
						<td><code>popup(title, message)</code></td>
						<td>Show a simple popup message</td>
						<td>
							<code>title</code> - Popup title<br>
							<code>message</code> - Popup content
						</td>
					</tr>
					<tr>
						<td><code>cleanUrl()</code></td>
						<td>Remove tracking parameters from URL</td>
						<td>None</td>
					</tr>
					<tr>
						<td><code>remove_param(param)</code></td>
						<td>Remove specific parameter from URL</td>
						<td><code>param</code> - Parameter name to remove</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Examples -->
		<div class="doc-section" id="examples">
			<h2 class="mb-4"><i class="bi bi-file-earmark-code me-2"></i> Examples</h2>
			
			<h4 class="mt-4">Basic Tracking Setup</h4>
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;script src="actpro.js"&gt;&lt;/script&gt;
&lt;script&gt;
// Configure with your API endpoint
ACTPRO.setup({
endpoint: 'https://your-domain.com/api',
cleanUrl: true
});

// Initialize tracking
ACTPRO.init();
&lt;/script&gt;</code>
				</div>
			</div>
			
			<h4 class="mt-4">Offerwall Integration</h4>
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;button id="download-btn" class="btn btn-primary"&gt;Download File&lt;/button&gt;

&lt;script&gt;
ACTPRO.setup({
offerwall: {
	trigger: '#download-btn',
	required_conversion: 2,
	title: 'Almost There!',
	text: 'Complete 2 offers to download your file.',
	buttonText: 'Download Now',
	buttonClick: function() {
		// Start download when button is clicked
		window.location.href = '/download-file';
	}
}
});

ACTPRO.init();
&lt;/script&gt;</code>
				</div>
			</div>
			
			<h4 class="mt-4">Manual Offerwall Trigger</h4>
			<div class="code-container">
				<div class="code-header">
					<span>HTML</span>
					<button class="copy-btn">Copy</button>
				</div>
				<div class="code-body">
					<code>&lt;button onclick="ACTPRO.offerwall()" class="btn btn-primary"&gt;
View Offers
&lt;/button&gt;

&lt;button onclick="ACTPRO.confirm('Your Code', 'Here is your code: ')" class="btn btn-secondary"&gt;
Check Completion
&lt;/button&gt;</code>
				</div>
			</div>
		</div>

		<!-- Troubleshooting -->
		<div class="doc-section" id="troubleshooting">
			<h2 class="mb-4"><i class="bi bi-question-circle me-2"></i> Troubleshooting</h2>
			
			<h4 class="mt-4">Common Issues</h4>
			<div class="accordion" id="troubleshootingAccordion">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue1">
							Tracking parameters aren't being saved
						</button>
					</h2>
					<div id="issue1" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
						<div class="accordion-body">
							<p>Make sure:</p>
							<ul>
								<li>You've called <code>ACTPRO.init()</code> after configuration</li>
								<li>Cookies are enabled in the browser</li>
								<li>Your domain doesn't have strict cookie policies</li>
								<li>You're not in private/incognito mode (which may block cookies)</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue2">
							Offerwall isn't showing up
						</button>
					</h2>
					<div id="issue2" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
						<div class="accordion-body">
							<p>Check these points:</p>
							<ul>
								<li>The trigger selector matches exactly your element's ID/class</li>
								<li>There are no JavaScript errors in the console</li>
								<li>Your API endpoint is correctly configured and responding</li>
								<li>You're not blocking the popup with an ad blocker</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue3">
							Postbacks aren't being sent
						</button>
					</h2>
					<div id="issue3" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
						<div class="accordion-body">
							<p>Troubleshooting steps:</p>
							<ul>
								<li>Verify you have a <code>click_id</code> parameter</li>
								<li>Check your network tab to see if the request is being made</li>
								<li>Ensure your API endpoint is correct</li>
								<li>Verify your server is accepting the postback data</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<h4 class="mt-4">Debugging Tips</h4>
			<ul>
				<li>Check browser console for JavaScript errors</li>
				<li>Inspect network requests to your API endpoint</li>
				<li>Verify cookie values with <code>document.cookie</code></li>
				<li>Use <code>console.log(ACTPRO.params)</code> to check current parameters</li>
			</ul>
		</div>
	</div>
</div>

<script>
	// Copy button functionality
	document.addEventListener('DOMContentLoaded', function() {
		// Add copy functionality to all copy buttons
		document.querySelectorAll('.copy-btn').forEach(button => {
			button.addEventListener('click', function() {
				const codeBlock = this.closest('.code-container').querySelector('.code-body code');
				const textToCopy = codeBlock.textContent;
				
				navigator.clipboard.writeText(textToCopy).then(() => {
					// Change button text temporarily
					const originalText = this.textContent;
					this.textContent = 'Copied!';
					this.classList.add('copied');
					
					setTimeout(() => {
						this.textContent = originalText;
						this.classList.remove('copied');
					}, 2000);
				}).catch(err => {
					console.error('Failed to copy text: ', err);
				});
			});
		});
		
		// Smooth scrolling for anchor links
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function(e) {
				e.preventDefault();
				
				const targetId = this.getAttribute('href');
				const targetElement = document.querySelector(targetId);
				
				if (targetElement) {
					window.scrollTo({
						top: targetElement.offsetTop - 20,
						behavior: 'smooth'
					});
				}
			});
		});
	});
</script>