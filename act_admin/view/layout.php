<?php

    /* Prevent Direct Access */
    defined( 'BASE' ) or die( 'No Direct Access!' );

?>
<?php echo View::component('include/header') ?>
    <?php echo $content; ?>
<?php echo View::component( 'include/footer' ); ?>