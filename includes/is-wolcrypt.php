<?php
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}
	
if ( ! function_exists( 'is_wcr_wp_version_ok' ) ){
	
	/**
	 * is_wcr_wp_version_ok function.
	 * 
	 * @access public
	 * @return void
	 */
	function is_wcr_wp_version_ok(){
		
		return wcr()->is->is_wp_version_ok();
		
	}
}


if ( ! function_exists( 'is_wcr_crypt_configurated' ) ){
	
	/**
	 * is_wcr_crypt_configurated function.
	 * 
	 * @access public
	 * @return void
	 */
	function is_wcr_crypt_configurated(){
		
		return wcr()->is->is_crypt_configurated();
		
	}
}


if ( ! function_exists( 'is_wcr_crypt_active' ) ){
	
	/**
	 * is_wcr_crypt_active function.
	 * 
	 * @access public
	 * @return void
	 */
	function is_wcr_crypt_active(){
		
		return wcr()->is->is_crypt_active();
		
	}
}