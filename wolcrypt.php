<?php
/**
 * @package WolCrypt
 * @author Paolo Valenti
 * @version 1.0 first release
 */
/*
Plugin Name: WolCrypt
Plugin URI: http://goodpress.it
Description: This plugin add encryption to WordPress
Author: Paolo Valenti aka Wolly
Version: 1.0
Author URI: https://paolovalenti.info
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wolcrypt
Domain Path: /languages
*/
/*
	Copyright 2013  Paolo Valenti aka Wolly  (email : wolly66@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

register_activation_hook( WP_PLUGIN_DIR . '/wolcrypt/wolcrypt.php', 'Wolcrypt::activate');

if ( ! class_exists( 'Wolcrypt' ) ) {
	
	final class Wolcrypt{
		
		/** Singleton *************************************************************/

		/**
		 * wolcrypt instance.
		 *
		 * @access private
		 * @since  1.0
		 * @var    wolcrypt The one true wolcrypt where the magic happens
		 */
		private static $instance;

		/**
		 * The version number of wolcrypt.
		 *
		 * @access private
		 * @since  1.0
		 * @var    string
		 */
		private $version = '1.0.0';
		
		/**
		 * $is
		 * 
		 *
		 * @since  1.0
		 * @var mixed
		 * @access public
		 */
		var $is;
		
		/**
		 * $enc_dec
		 * 
		 *
		 * @since  1.0
		 * @var mixed
		 * @access public
		 */
		var $enc_dec;
		
		
		/**
		 * Main wolcrypt Instance
		 *
		 * Insures that only one instance of wolcrypt exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since     1.0
		 * @static
		 * @staticvar array $instance
		 * @uses      wolly-contracts::setup_globals() Setup the globals needed
		 * @uses      wolly-contracts::includes() Include the required files
		 * @uses      wolly-contracts::setup_actions() Setup the hooks and actions
		 * @uses      wolly-contracts::updater() Setup the plugin updater
		 * @return wolly-contracts
		 */
		public static function instance() {
			
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Wolcrypt ) ) {
				self::$instance = new Wolcrypt;
				
				global $wp_version;
				if ( version_compare( $wp_version, '5.2', '<' ) ) {

					add_action( 'admin_notices', array(
						'Wolcrypt',
						'below_wp_version_notice'
					) );
					return self::$instance;

				}
								
				self::$instance->setup_constants();
				
				self::$instance->includes();
				
				add_action( 'plugins_loaded', array(
					self::$instance,
					'setup_objects'
				), - 1 );
							 					
			}

			return self::$instance;
		}
		
		/**
		 * activate function.
		 * 
		 * @since     1.0
		 * @access public
		 * @static
		 * @return void
		 */
		public static function activate(){
		
		if ( ! file_exists( ABSPATH . 'wcr-config.php' ) ) {
			
			$file = ABSPATH . 'wcr-config.php';
			$enc_key =   bin2hex( sodium_crypto_secretbox_keygen() );
			
			// Append a new person to the file
			$current = "<?php \n";
			$current .= "define( 'WOLCRYPT_ENC_KEY', '" . $enc_key . "' );\n";
			
			// Write the contents back to the file
			file_put_contents($file, $current);
			
		}
		
	}
		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since  1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wolcrypt' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since  1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wolcrypt' ), '1.0' );
		}
		
		/**
		 * Show a warning to sites running PHP < 5.6
		 *
		 * @static
		 * @access private
		 * @since  1.0
		 * @return void
		 */
		public static function below_wp_version_notice() {
			echo '<div class="error"><p>' . __( 'Your version of WordPress is below the minimum version of WordPress required by Wolcrypt. Please, upgrade your WordPress to 5.2 or later.', 'wolcrypt' ) . '</p></div>';
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since  1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			if ( ! defined( 'WOLCRYPT_PLUGIN_VERSION' ) ) {
				define( 'WOLCRYPT_PLUGIN_VERSION', $this->version );
			}
			
			// Plugin version option name
			if ( ! defined( 'WOLCRYPT_PLUGIN_VERSION_NAME' ) ) {
				define( 'WOLCRYPT_PLUGIN_VERSION_NAME', 'wolcrypt_version' );
			}

			// Plugin Folder Path
			if ( ! defined( 'WOLCRYPT_PLUGIN_PATH' ) ) {
				define( 'WOLCRYPT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'WOLCRYPT_PLUGIN_DIR' ) ) {
				define( 'WOLCRYPT_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'WOLCRYPT_PLUGIN_FILE' ) ) {
				define( 'WOLCRYPT_PLUGIN_FILE', __FILE__ );
			}
			
			// Plugin Slug
			if ( ! defined( 'WOLCRYPT_PLUGIN_SLUG' ) ) {
				define( 'WOLCRYPT_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
			}
			
			// Encrypt COSTANT
			if ( file_exists( ABSPATH . 'wcr-config.php' ) ) {
				
				require_once ABSPATH . 'wcr-config.php';
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @since  1.0
		 * @return void
		 */
		private function includes() {
									
			require_once WOLCRYPT_PLUGIN_PATH . 'includes/wolcrypt-encrypt-function.php';
			
			require_once WOLCRYPT_PLUGIN_PATH . 'includes/class-wolcrypt-is.php';
			
			require_once WOLCRYPT_PLUGIN_PATH . 'includes/is-wolcrypt.php';
			
			require_once WOLCRYPT_PLUGIN_PATH . 'includes/class-wolcrypt-encrypt-decrypt.php';
								
			// Admin only used class
			if ( is_admin() ) {
				
				require_once WOLCRYPT_PLUGIN_PATH . 'includes/class-wolcrypt-settings.php';
				
			} else {
				
													
			}
			
								
		}

		/**
		 * Setup all objects
		 *
		 * @access public
		 * @since  1.6.2
		 * @return void
		 */
		public function setup_objects() {

			self::$instance->is			= new Wolcrypt_Is();
			
			self::$instance->enc_dec	= new Wolcrypt_Enc_Dec();
			
			// Instantiate in admin only
			if ( is_admin() ) {
											
				
			} else {
				
												

			}
						
			self::$instance->updater();
			
			
		
		}
		
		/**
		 * Plugin Updater
		 *
		 * @access private
		 * @since  1.0
		 * @return void
		 */
		private function updater() {

			//TODO: Maybe

		}

	}
	
}

/**
 * The main function responsible for returning the one true wolcrypt
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wolcrypt = wolcrypt(); ?>
 *
 * @since 1.0
 * @return wolcrypt The one true wolcrypt Instance
 */
function wcr() {
	
	return Wolcrypt::instance();
}

wcr();
