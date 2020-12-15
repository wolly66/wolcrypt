<?php

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

if ( ! function_exists( 'wcr_enc' ) ){
	
	/**
	 * wcr_enc function.
	 * 
	 * @access public
	 * @param string $string (default: '')
	 * @return string
	 */
	function wcr_enc( $string = '' ){
		
		return wcr()->enc_dec->encrypt( $string );
		
	}
}

if ( ! function_exists( 'wcr_dec' ) ){
	
	/**
	 * wcr_dec function.
	 * 
	 * @access public
	 * @param string $ciphertext (default: '')
	 * @return string
	 */
	function wcr_dec( $ciphertext = '' ){
		
		return wcr()->enc_dec->decrypt( $ciphertext );
		
	}
}

