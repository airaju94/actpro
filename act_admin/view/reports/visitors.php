<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="p-4 shadow-sm border mb-3 clearfix rounded-2">
    <h3 class="fs-5 d-block text-secondary mb-3">Generate Report</h3>
    <div class="row row-cols-1 justify-content-center g-3">
        <div class="col">
            <?php if( isset( $dimension ) && count( $dimension ) > 0 ): ?>
                <small>Select Dimension</small>
                <select id="dimension" class="form-select form-select-sm">
                    <?php foreach( $dimension as $option ): ?>
                        <option value="<?php echo $option ?>"><?php echo ucwords( str_replace('_', ' ', $option) ) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="col">
            <small>Where</small>
            <div class="input-group input-group-sm">
                <?php if( isset( $dimension ) && count( $dimension ) > 0 ): ?>
                    <select id="where" class="form-select form-select-sm">
                        <?php foreach( $dimension as $option ): ?>
                            <option value="<?php echo $option ?>"><?php echo ucwords( str_replace('_', ' ', $option) ) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control form-control-sm" placeholder="Query: source name, medium, offer id etc." id="where_query" aria-label="where query">
            </div>
        </div>
        <div class="col">
            <small>Timeframe</small>
            <select id="timeframe" class="form-select form-select-sm">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="7days">7 Days</option>
                    <option value="30days" selected>30 Days</option>
                    <option value="60days">60 Days</option>
                    <option value="90days">90 Days</option>
            </select>
        </div>
    </div>
    <button class="btn btn-sm btn-primary float-end mt-3" onclick="generate_report(this)"><i class="bi bi-arrow-repeat"></i> Generate</button>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Visitors Chart</h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="visitorsChart"></canvas>
        </div>
    </div>
</div>
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <div>
                <h5 class="mb-0 mt-1">Visitors Report</h5>
            </div>
            <div>
                <button class="btn btn-sm btn-light py-0 rounded-1" onclick="exportTableCSV('#visitorsTable')"><i class="bi bi-filetype-csv me-1"></i> Export</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="visitorsTable"></div>
            </div>
        </div>
    </div>
</div>
<script>
    const reportType = 'visitors';
    var dimension = 'date';
    var timeframe = '30days';

    function generate_report( el ){
        var dimension = document.getElementById('dimension').value;
        timeframe = document.getElementById('timeframe').value;
        var where_select = document.getElementById('where').value;
        var where_query = document.getElementById('where_query').value;
        if( where_query !='' ){
           var where = where_select+'_'+where_query;
        }
        update_report( reportType, timeframe, dimension, where );
    }

    window.addEventListener('load', () => {
        update_report( reportType, timeframe, dimension );
    })

</script>
