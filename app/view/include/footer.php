<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<?php if( !self::$noFooter ): ?>
    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 YTSBLOG. Not responsible for extreme curiosity or unexpected surprises.</p>
        </div>
    </footer>
<?php endif; ?>
<?php if( e::has() ): ?>
    <?php echo getMessage(); ?>
<?php endif; ?>
    <div id="toastContainer"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo Url::baseUrl() ?>/app/view/js/controller.js?v=1"></script>
    <?php if( isset( $footer_code ) ): ?>
        <?php echo $footer_code ?>
    <?php endif; ?>
</body>
</html>