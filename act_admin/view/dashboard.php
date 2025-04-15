<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!-- Dashboard -->
<div class="row row-cols-sm-1 row-cols-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
        <div class="card stat-card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value" id="visitor">0</div>
                        <div class="label">Visitors</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <hr />
                <div class="row row-cols-3 g-2 text-center">
                    <div class="col">
                        <div class="d-block fw-bold sub-card" id="clicks">0</div>
                        <div class="d-block sub-card-icon"><i class="bi bi-mouse"></i> Clicks</div>
                    </div>
                    <div class="col border-start">
                        <div class="d-block fw-bold sub-card"><span id="ctr">0.00</span>%</div>
                        <div class="d-block sub-card-icon"><i class="bi bi-percent"></i> CTR</div>
                    </div>
                    <div class="col border-start">
                        <div class="d-block fw-bold sub-card">$<span id="cost">0.00</span></div>
                        <div class="d-block sub-card-icon"><i class="bi bi-coin"></i> Cost</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value" id="conversion">0</div>
                        <div class="label">Conversions</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <hr />
                <div class="row row-cols-3 g-2 text-center">
                    <div class="col">
                        <div class="d-block fw-bold sub-card"><span id="cr">0.00</span>%</div>
                        <div class="d-block sub-card-icon"><i class="bi bi-percent"></i> CR</div>
                    </div>
                    <div class="col border-start">
                        <div class="d-block fw-bold sub-card">$<span id="epv">0.00</span></div>
                        <div class="d-block sub-card-icon"><i class="bi bi-coin"></i> EPV</div>
                    </div>
                    <div class="col border-start">
                        <div class="d-block fw-bold sub-card">$<span id="epc">0.00</span></div>
                        <div class="d-block sub-card-icon"><i class="bi bi-cash"></i> EPC</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="value">$<span id="earnings">0.00</span></div>
                        <div class="label">Earnings</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                <hr />
                <div class="text-start">
                    <i class="bi bi-info-circle-fill me-2"></i><small>Earnings are estimated, not confirmed.</small>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Performance Overview</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="performance"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Countries</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topCountries"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Network</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topNetwork"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Offers</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topOffers"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Source</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topSource"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Medium</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topMedium"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Top Zone Id</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topZoneId"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <div>
                <h5 class="mb-0 mt-1">Recent Conversions</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-light py-0 rounded-1" onclick="exportTableCSV('#recentConversion')"><i class="bi bi-filetype-csv me-1"></i> Export</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="recentConversion"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const reportType = 'dashboard';
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
        update_report( reportType, 'today' );
    })
</script>