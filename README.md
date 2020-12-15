# wolcrypt
A plugin to add crypto functionality to WordPress using libsodium as it's included in WP since 5.2

To encrypt string use wcr_enc( $string ). 
wcr_enc function return the string encrypted

To decrypt encrypted string, use wcr_dec( $ciphertext ).
wcr_dec( $ciphertext ) retrun $ciphertext decrypted
