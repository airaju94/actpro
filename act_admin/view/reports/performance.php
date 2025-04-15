<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Performance Chart</h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>
</div>
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <div>
                <h5 class="mb-0 mt-1">Performance Report</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-light py-0 rounded-1" onclick="exportTableCSV('#performanceTable')"><i class="bi bi-filetype-csv me-1"></i> Export</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="performanceTable"></div>
            </div>
        </div>
    </div>
</div>
<script>
    const reportType = 'performance';
    var range = document.querySelectorAll('[data-range]');
    var rangeBtn = document.querySelector('.range-btn');
    if( range.length > 0 ){
        range.forEach(timeframe => {
            timeframe.addEventListener('click', (e) => {
                rangeBtn.innerHTML = e.target.innerHTML;
                var dateRange = e.target.getAttribute('data-range');
                update_report( reportType, dateRange );
            })
        })
    }
    window.addEventListener('load', () => {
        update_report( reportType, '30days' );
        rangeBtn.innerHTML = '30 Days'
    })
</script>
