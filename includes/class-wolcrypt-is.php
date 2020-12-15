<?php
	
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wolcrypt_Is' ) ){
	
	/**
	 * Wolcrypt_Is class.
	 */
	class Wolcrypt_Is {
		
		/**
		 * options
		 * 
		 * @var mixed
		 * @access private
		 */
		private $options;
		
		
		/**
		 * __construct function.
		 * 
		 * @access public
		 * @return void
		 */
		public function __construct(){
			
			$this->options = get_option( 'wcr-options' );
		}
		
		/**
		 * is_wp_version_ok function.
		 * 
		 * @access public
		 * @return BOOL
		 */
		public function is_wp_version_ok(){
			
			global $wp_version;
			
			if ( version_compare( $wp_version, '5.2', '>=' ) ) {
       		
       			return TRUE;
       		}
       		
       		return FALSE;
			
		}
		
		/**
		 * is_crypt_configurated function.
		 * 
		 * @access public
		 * @return BOOL
		 */
		public function is_crypt_configurated(){
			
			if ( file_exists( ABSPATH . 'wcr-config.php' ) ) {
				
				if ( defined( 'WOLCRYPT_ENC_KEY' ) && ! empty( 'WOLCRYPT_ENC_KEY' ) ){
					
					return TRUE;
				}
				
				return FALSE;
				
			}
			
			return FALSE;
		}
		
		/**
		 * is_crypt_active function.
		 * 
		 * @access public
		 * @return BOOL
		 */
		public function is_crypt_active(){
			
			$this->options['activated'] = TRUE;
			
			if ( TRUE == $this->options['activated'] && TRUE == $this->is_crypt_configurated() ){
				
				return TRUE;
			}
			
			return FALSE;
			
		}
	}
}