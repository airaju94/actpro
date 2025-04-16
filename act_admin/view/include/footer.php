<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<?php if( !self::$noFooter ): ?>
        </div><!-- Fluid container: Main Content -->
    </div><!-- Content -->


    <div class="offcanvas-backdrop" id="offcanvasBackdrop" style="display: none"></div>
<?php endif; ?>
<?php if( Auth::check() && e::has() ): ?>
    <?php echo getMessage(); ?>
<?php endif; ?>
    <div id="toastContainer"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Url::baseUrl() ?>/view/js/controller.js?v=1"></script>
    <script src="<?php echo Url::baseUrl() ?>/view/js/main.js?v=1.1"></script>
    <script src="<?php echo Url::baseUrl() ?>/view/js/tableSocter.js?v=1.5"></script>
    <?php if( isset( $footer_code ) ): ?>
        <?php echo $footer_code ?>
    <?php endif; ?>
</body>
</html>