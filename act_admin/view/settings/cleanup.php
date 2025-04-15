<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Database Clean Up</h5>
    </div>
    <div class="card-body">
        <div class="p-3 bg-danger bg-opacity-10 text-start m-auto border border-danger border-2 border-opacity-25 rounded-4">
            <h3 class="fw-bold fs-2 p-0 m-0">Danger Zone</h3>
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>Today</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('today', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>Yesterday</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('yesterday', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>last 7 days</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('7days', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>last 30 days</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('30days', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>last 60 days</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('60days', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>last 90 days</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('90days', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            <hr />
            
            <div class="d-block p-2 mt-3 mb-3">
                <small class="d-block text-start">Delete <b>All</b> data for all tables: (<b>visitor, clicks, conv, stats</b>). expect offers.</small>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="delete_data('all', this)"><i class="bi bi-trash"></i> Delete Now</button>
            </div>
            
        </div>
    </div>
</div>
<script>
    function delete_data(range, el){
        if( range =='' || !el ){
            return false;
        }
        
        var conf = prompt("Please type DELETE to confirm.");
        if( conf !== "DELETE" ){
            return false;
        }
        el.setAttribute('disabled', true);
        
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: '<?php echo Url::baseUrl() ?>/settings/cleanup',
            data: {range: range, isAjax: true},
            error: function(xhr, status, message){
                showToast( status+' :: '+message, 'error' );
            },
            success: function( res ){
                prepareMessage( res );
                el.removeAttribute('disabled');
            }
        })
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
</script>