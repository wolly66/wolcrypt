<?php
	
if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wolcrypt_Enc_Dec' ) ){
	
	/**
	 * Wolcrypt_Enc_Dec class.
	 */
	class Wolcrypt_Enc_Dec {
		
		/**
		 * create_nonce function.
		 * 
		 * @access public
		 * @return nonce
		 */
		public function create_nonce(){
			
			$nonce = bin2hex( random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ) );
			
			return $nonce;
		}
		
		/**
		 * get_key function.
		 * 
		 * @access public
		 * @return key
		 */
		public function get_key(){
			
			$key = WOLCRYPT_ENC_KEY;
			
			return $key;
			
		}
		
		/**
		 * encrypt function.
		 * 
		 * @access public
		 * @param mixed $string
		 * @return string
		 */
		public function encrypt( $string ){
			
			
			if ( is_wcr_wp_version_ok() ){
				
				$key = $this->get_key();
				
				$nonce = $this->create_nonce();
				$ciphertext = sodium_crypto_secretbox( $string, hex2bin( $nonce ), hex2bin( $key )  );
				
				$string_to_save = $nonce . '__' . bin2hex( $ciphertext );
				sodium_memzero( $string );
				sodium_memzero( $key );
				
				return $string_to_save;
			}
			
			return $string;
		}
		
		/**
		 * decrypt function.
		 * 
		 * @access public
		 * @param mixed $string
		 * @return string
		 */
		public function decrypt( $ciphertext ){
			
			if ( is_wcr_wp_version_ok() ){
				$key = $this->get_key();
				$ciphertext_array = explode( '__', $ciphertext );
				
				
				$ciphertext_message = hex2bin( $ciphertext_array[1] );
				$ciphertext_nonce = hex2bin( $ciphertext_array[0] );
								
				$plaintext = sodium_crypto_secretbox_open( $ciphertext_message ,  $ciphertext_nonce, hex2bin( $key ) );
								 
				sodium_memzero( $ciphertext );
				sodium_memzero( $key );
				
				return $plaintext;
			}
			
			return $ciphertext;
			
			
		}
	}
}
