<?php
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}


/**
 * Wolcrypt_Settings class.
 */
class Wolcrypt_Settings
{
	
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        //add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Crypt Settings', 
            'manage_options', 
            'wcr-crypt-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'wcr-options' );
        ?>
        <div class="wrap">
            <h2><?php _e( 'Crypto settings', 'wolcrypt' ); ?></h2>  
            <p><?php _e( 'The activation of the Cripto functions is a two step process. First step is the creation of the config file and the second step is the activation.', 'wolcrypt' ); ?></p>
            
            <p><?php _e( 'It\'s very important that you make a copy of the generated file <pre>wcr-config.php</pre> in the root of your WordPress installation, as if you delete it, or you change the key, you will not be able to decrypt all your previous encrypted string.', 'wolcrypt' ); ?></p>         
            <form method="post" action="options.php">
            <?php
	            if ( is_writable( ABSPATH ) ){
		            
		            
	            }
	        ?>
            
            </form>
        </div>
        <?php
    }

   }

if( is_admin() )
    $wolcrypt_settings = new Wolcrypt_Settings();