    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })


    /**
     * Bootstrap Toast
     * @name showToast
     * @param message
     * @param type 
     * @param options (optional)
     */
    function showToast(message, type = 'primary', options = {}) {
        // Default options
        const defaultOptions = {
            delay: 5000,
            autohide: true
        };
        const mergedOptions = { ...defaultOptions, ...options };

        // Type mapping
        const typeMap = {
            primary: { 
                icon: 'bi-stars', 
                color: 'text-success',
                bg: 'bg-primary-subtle'
            },
            success: { 
                icon: 'bi-check-circle-fill', 
                color: 'text-success',
                bg: 'bg-success-subtle'
            },
            error: { 
                icon: 'bi-exclamation-triangle-fill', 
                color: 'text-danger',
                bg: 'bg-danger-subtle'
            },
            warning: { 
                icon: 'bi-exclamation-triangle-fill', 
                color: 'text-warning',
                bg: 'bg-warning-subtle'
            },
            info: { 
                icon: 'bi-info-circle-fill', 
                color: 'text-info',
                bg: 'bg-info-subtle'
            }
        };

        // Get type config or default to info
        const typeConfig = typeMap[type] || typeMap.info;

        // Create toast element
        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        const toastEl = document.createElement('div');
        toastEl.className = 'toast toast-'+type;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.setAttribute('data-bs-autohide', mergedOptions.autohide);
        toastEl.setAttribute('data-bs-delay', mergedOptions.delay);
        toastEl.id = toastId;

        // Toast content
        toastEl.innerHTML = `
            <div class="toast-header ${typeConfig.bg}">
                <i class="${typeConfig.icon} ${typeConfig.color} me-2 fs-6"></i>
                <strong class="me-auto text-capitalize">${type}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

        // Add to container
        document.getElementById('toastContainer').appendChild(toastEl);

        // Initialize and show toast
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        // Remove after hidden
        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }

	/* Prepare Message for display */
	function prepareMessage( response ){
		if( response.hasOwnProperty( 'e' ) && response.e !== false){
			if( response.e.length > 0 ){
				response.e.forEach((i, o) => {
					showToast( i.message, i.type ) ;
				})
			}
		}
	}


    // Theme toggle
	function switchTheme(e){
		var currentThemeColor = document.body.getAttribute('data-bs-theme');
		var lightThemeIcon = '<i class="bi bi-sun"></i>';
		var darkThemeIcon = '<i class="bi bi-moon-stars"></i>';
		var changeThemeTo = currentThemeColor === 'light' ? 'dark':'light';
		var changeThemeIconTo = changeThemeTo === 'light' ? darkThemeIcon:lightThemeIcon;
		document.body.setAttribute('data-bs-theme', changeThemeTo);
		e.innerHTML = '';
		e.innerHTML = changeThemeIconTo;
		Cookie.set( 'actpro_theme', changeThemeTo, 30 );
	}

    // Mobile sidebar toggle
    const sidebar = document.getElementById('sidebar')
    const sidebarToggle = document.getElementById('sidebarToggle')
    const offcanvasBackdrop = document.getElementById('offcanvasBackdrop')

    if( sidebar ){
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            if( sidebar.classList.contains('active') ){
                offcanvasBackdrop.style.display = 'block';
            }else{
                offcanvasBackdrop.style.display = 'none';
            }
            
        })
        offcanvasBackdrop.addEventListener('click', function() {
            sidebar.classList.remove('active');
            this.style.display = 'none';
        })
    }



	var navigationLink = document.querySelector( 'li a[href="'+Url.currentUrl()+'"]' );
	if( navigationLink ){
		navigationLink.classList.add( 'active' );
	}	

	function loader( text = 'Please Wait..' ){
		return '<div class="d-flex flex-row justify-content-center"><div class="spinner-border"></div><div class="ms-1">'+text+'</div></div>';
	}
	
	/* Button text */
	function save_changes(){
		return '<i class="bi bi-check-circle-fill me-1"> Save Changes';
	}

    function preloader( action = 'create' ){
        var cards = document.querySelectorAll( '.card' );
        if( action == 'create' ){
            if( cards.length > 0 ){
                cards.forEach(card => {
                    var preloader = document.createElement( 'div' );
                    preloader.setAttribute('class', 'preloader');
                    card.append( preloader );
                    card.classList.add('card-preloader');
                })
            }
        }else if( action == 'remove' ){
            if( cards.length > 0 ){
                cards.forEach(card => {
                    if( card.classList.contains('card-preloader') ) card.classList.remove('card-preloader');
                })
    
                document.querySelectorAll('div.preloader').forEach(i => { i.remove(); })
            }
        }
    }


    /**
     * 
     * Dynamic Chart Generatio Function 
     * Library: ChartJS
     */
    function chartJS( chartId, data, type = 'line' ){

        let chartStatus = Chart.getChart(chartId); // <canvas> id
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }

        var ctx = document.getElementById( chartId );
        if( ctx.tagName !== 'CANVAS' ) return false;
        // console.log( data );
        // data = JSON.parse( data );
        data.type = data.type ?? type;

        ctx.getContext('2d');
        const myChart = new Chart(ctx, {
            type: data.type,
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                devicePixelRatio: 2,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })
    }
    /**
     * Reporting API function
     * @param report report type eg: overview, earnings, conversion, source, medium, zoneId, network, countries
     * @param range timeframe for generating reports eg: today, yesterday, 7days, 30days, 60days, 90days max. 
     * @note report render accourding to ids placed on the webpage.
     */
    function getReport( endpoint ){
        if( endpoint == '' ){
            return false;
        }
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: endpoint,
            processData: false,
            contentType: false,
            error: function( xhr, status, mess ){
                showToast( status+' :: '+mess );
            },
            success: function(e){

                // for display message in toast.
                prepareMessage( e );

                if( e.hasOwnProperty( 'cards' ) ){
                    for( c in e.cards ){
                        document.getElementById( c ).innerHTML = e.cards[c];
                    }
                }

                if( e.hasOwnProperty( 'charts' ) ){
                    for( ctx in e.charts ){
                        chartJS( ctx, e.charts[ctx] );
                    }
                }

                if( e.hasOwnProperty( 'tables' ) ){
                    for( tb in e.tables ){
                        document.getElementById(tb).innerHTML = e.tables[tb];
                    }
                    var tsTime = setInterval(()=>{
                        clearInterval(tsTime);
                        new tableSocter(".table");
                    }, 300);
                }
                preloader('remove');
            }
        })
    }
    /**
     *  For fetching charts from api. 
     */
    function update_report( reportType = 'dashboard', range = 'today', dimension = '' ){
        if( reportType == '' || range == '' ){ return false; }
        var endpoint = '?report='+reportType+'&range='+range;
        dimension = dimension ? '&dimension='+dimension:'';
        endpoint = reporting_api_endpoint+endpoint+dimension;
        preloader('create');
        getReport( endpoint );
    }

    /**
     * Exports an HTML table to a CSV file with auto-generated filename
     * @param {HTMLTableElement} table - The table element to export
     */
    function exportTableCSV( selector ) {
        if( selector == '' ){ return false }

        var table = document.querySelector( selector );
        if( !table ){ showToast('Table not found for export!', 'error'); return false; }

        // Generate a unique filename with timestamp
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
        const filename = `table-export-${timestamp}.csv`;
        
        // Initialize CSV content
        let csv = [];
        // Get all rows from the table
        const rows = table.querySelectorAll('tr');
        
        // Loop through each row
        for (const row of rows) {
            const rowData = [];
            
            // Get all cells (td/th) from the current row
            const cells = row.querySelectorAll('td, th');
            
            // Loop through each cell
            for (const cell of cells) {
                // Clean the cell content and add quotes if needed
                let cellText = cell.textContent.trim();
                
                // Escape quotes by doubling them
                cellText = cellText.replace(/"/g, '""');
                
                // Wrap in quotes if contains commas, newlines, or quotes
                if (/[,"\n]/.test(cellText)) {
                    cellText = `"${cellText}"`;
                }
                
                rowData.push(cellText);
            }
            
            // Add the row to CSV
            csv.push(rowData.join(', '));
        }
        
        // Combine all rows into a single string with BOM for UTF-8
        const csvString = '\uFEFF' + csv.join('\n');
        
        // Create a Blob with the CSV data
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        
        // Create a download link
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        // Set the link attributes
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        // Add the link to the DOM and trigger the download
        document.body.appendChild(link);
        link.click();
        
        // Clean up
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }