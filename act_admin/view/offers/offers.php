<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!-- Offers -->
<?php if( isset( $offers ) && !empty( $offers ) ){ ?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 m-auto justify-content-center">
    <?php echo $offers; ?>
</div>
<?php }else{ ?>
<div class="py-5 text-center">
    <div class="text-center text-muted"><i class="bi bi-info-circle fs-1"></i></div>
    <h3 class="text-muted">No offers we are found!</h3>
</div>
<?php } ?>

<!-- Add Offer Modal -->
<div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Add New Offer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-0 pb-0">
                <form action="<?php echo Url::baseUrl() ?>/offers/add" method="post" id="offerForm">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Offer title</label>
                            <input type="text" class="form-control form-control-sm" id="title" name="title" maxlength="60" required focus />
                        </div>
                        <div class="col-md-6">
                            <?php if( isset( $categories ) && !empty( $categories ) && is_array($categories) ): ?>
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control form-control-sm" id="categoey" name="category" required focus>
                                    <?php foreach( $categories as $c ): ?>
                                        <option value="<?php echo $c ?>"><?php echo $c; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-sm-3 mb-3">
                        <div class="col mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control form-control-sm" id="status" name="status" required focus>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col mb-3">
                            <label for="time" class="form-label">Estimate Time</label>
                            <select class="form-control form-control-sm" id="time" name="time" required focus>
                                <option value="10 seconds">10 Seconds</option>
                                <option value="20 seconds">20 Seconds</option>
                                <option value="30 seconds">30 Seconds</option>
                                <option value="40 seconds">40 Seconds</option>
                                <option value="50 seconds">50 Seconds</option>
                                <option value="1 minute">1 minute</option>
                                <option value="2 minutes">2 minute</option>
                                <option value="3 minutes">3 minute</option>
                                <option value="4 minutes">4 minute</option>
                                <option value="5 minutes">5 minute</option>
                                <option value="10 minutes">10 minute</option>
                                <option value="15 minutes">15 minute</option>
                                <option value="20 minutes">20 minute</option>
                                <option value="25 minutes">25 minute</option>
                                <option value="30 minutes">30 minute</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="network" class="form-label">Network</label>
                            <input type="text" class="form-control form-control-sm" id="network" name="network" required focus />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="3" maxlength="100" required focus></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">Offer Link</label>
                        <input type="url" class="form-control form-control-sm" id="link" name="link" placeholder="https://example.com/offer?tracking-parameter={{click_id}}" required />
                        <div class="alert alert-info mt-2" style="font-size:14px;">
                            <i class="bi bi-info-circle-fill me-1"></i>Must use <b>{{click_id}}</b> macro in your <b>offer link</b>. this is for tracking conversion.
                            <small class="fw-bold d-block mt-1 mb-1">Example:</small>
                            <ul>
                                <li>Third-Party offer: https://offerdomain.com/offer/?tracking=<b>{{click_id}}</b></li>
                                <li>Own offer: https://landing.page/?click_id=<b>{{click_id}}</b></li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer mt-4 mb-0 pb-3">
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="saveOffer"><i class="bi bi-floppy me-1"></i> Save Offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="offerLink" tabindex="-1" aria-labelledby="Offer link" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Offer Tracking Link</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 border bg-white rounded-1" style="font-size:14px;" id="offer_link">
                    <?php echo siteUrl() ?>/api/offer/go?offer_id=<span id="offer_id" class="fw-bold text-primary">{{offer_id}}</span>&source=<span class="fw-bold text-primary">{{source}}</span>&medium=<span class="fw-bold text-primary">{{medium}}</span>&zone_id=<span class="fw-bold text-primary">{{zone_id}}</span>&cost=<span class="fw-bold text-primary">{{cost}}</span>
                </div>
                <div class="alert alert-info text-break mt-3" style="font-size:13px;">
                    <i class="bi bi-info-circle me-2"></i><b>Important Note:</b> You must replace <b>{{source}}</b>, <b>{{medium}}</b>, <b>{{zone_id}}</b>, <b>{{cost}}</b> in these <b>Macros</b> with their respective value to track visitors, conversion, and clicks.<br /><br />
                    Example: https://example.com/api/offer/go?offer_id=1234&source=adsterra&medium=display&zone_id=sub_id&cost=0.001
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    const addOffer_endopint = '<?php echo Url::baseUrl() ?>/offers/add';
    const editOffer_endpoint = '<?php echo Url::baseUrl() ?>/offers/edit';
    const deleteOffer_endpoint = '<?php echo Url::baseUrl() ?>/offers/delete';
    
    function update_offer_page(){
        $.ajax({
            type: 'GET',
            url: '<?php echo Url::currentPageUrl() ?>',
            success: function( data ){
                if( data !== '' ){
                    document.getElementById('content').innerHTML = data;
                }
            }
        })
    }

    function add_offer(){
        var offerModal = $('#offerModal');
        var offerForm = document.querySelector( "#offerForm" );
        offerForm.reset();
        document.querySelector('#offerModal').addEventListener('shown.bs.modal', () => {
            var modalTitle = document.getElementById("modalTitle");
            modalTitle.innerHTML = 'Add New Offer';
            offerForm.addEventListener('submit', (form) => {
                form.preventDefault();
                var data = new FormData( offerForm );
                $.ajax({
                    type: 'POST',
                    url: form.target.action,
                    dataType: 'JSON',
                    data: data,
                    processData: false,
                    contentType: false,
                    error: function( xhr, status, message ){
                        showToast( status+' :: '+message, 'error' );
                    },
                    success: function( e ){
                        prepareMessage( e );
                        if( e.hasOwnProperty( 'status' ) ){
                            if( e.status === 'success' ){
                                form.target.reset();
                                offerModal.modal('hide');
                                update_offer_page()
                            }
                        }
                    }
                })
            })
        })
    }

    function edit_offer( offer_id ){
        var offerModal = $('#offerModal');
        var modalTitle = document.getElementById("modalTitle");

        $.ajax({
            type: "GET",
            url: editOffer_endpoint,
            data: {offer_id: offer_id},
            error: function( xhr, status, message ){
                showToast( status+' :: '+message, 'error' );
            },
            success: function( data ){
                if( data == '' || !data.hasOwnProperty('offer') ){
                    showToast( 'offer not found!' );
                }
                if( data.hasOwnProperty('offer') ){
                    for( k in data.offer ){
                        if( k == 'status' ){
                            data.offer[k] = data.offer[k] == 1 ? 'active' : 'inactive';
                        }
                        var el = document.querySelector('[name="'+k+'"]');
                        if( el ){
                            el.value = data.offer[k];
                        }
                    }
                    offerModal.modal('show');
                }
            }
        })

        document.querySelector('#offerModal').addEventListener('shown.bs.modal', () => {
            var offerForm = document.querySelector( "#offerForm" );
            modalTitle.innerHTML = 'Edit offer: #'+offer_id;
            offerForm.addEventListener('submit', (form) => {
                form.preventDefault();
                var data = new FormData( offerForm );
                data.append('id', offer_id);
                $.ajax({
                    type: 'POST',
                    url: editOffer_endpoint,
                    dataType: 'JSON',
                    data: data,
                    processData: false,
                    contentType: false,
                    error: function( xhr, status, message ){
                        showToast( status+' :: '+message, 'error' );
                    },
                    success: function( e ){
                        prepareMessage( e );
                        if( e.hasOwnProperty( 'status' ) ){
                            if( e.status === 'success' ){
                                form.target.reset();
                                offerModal.modal('hide');
                                update_offer_page()
                            }
                        }
                    }
                })
            })
        })
    }

    function delete_offer( id, el, confirm = false ){
        if( confirm == true ){
            $.ajax({
                type: 'POST',
                url: deleteOffer_endpoint,
                data: {offer_id:id},
                error: function( xhr, status, message ){
                    showToast( status+' :: '+message, 'error' );
                },
                success: function( e ){
                    prepareMessage( e );
                    if( e.hasOwnProperty( 'status' ) ){
                        el.innerHTML = '<i class="bi bi-trash"></i> Delete';
                        if( e.status === 'success' ){
                            update_offer_page()
                        }
                    }
                }
            })
        }else{
            var btn = el.innerHTML;
            el.innerHTML = '<i class="bi bi-trash"></i> Confirm <span class="delTime">5</span>';
            el.setAttribute( 'onclick', 'delete_offer('+id+', this, '+true+')' );
            var delTime = document.querySelector(".delTime");
            var deleteTime = 5;
            var delTimer = setInterval(() => {
                if( deleteTime === -1 ){
                    clearInterval(delTimer);
                    delTime.remove();
                    el.innerHTML = btn;
                    el.setAttribute( 'onclick', 'delete_offer('+id+', this)' );
                }
                deleteTime = (deleteTime - 1);
                delTime.innerHTML = deleteTime;
            }, 1000);
        }
    }

    function get_link(id){
        var linkModal = $('#offerLink');
        linkModal.modal('show');
        document.getElementById('offerLink').addEventListener('shown.bs.modal', () => {
            document.getElementById('offer_id').innerHTML = id;
        })
    }

</script>