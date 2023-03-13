<?php
/************************************************************************************************************************************************
 *
 * Class:  Cryptor  
 * 
 * PHP Encryption and decryption class with open_ssl
 * 
 * Works also with larger text (because text is split in smaller parts).
 * Generates a random IV with openssl_random_pseudo_bytes for each message and is prefixed to the encrypted_text.
 * Generates a random nonce (number used once) with openssl_random_pseudo_bytes used as salt for each message. 
 * Purpose of random IV and nonce: When the same message is encrypted twice, the encrypted_text is always different.
 * IVs do not have to be kept secret. They are prefixed to the encrypted_text and transmitted in full public view.
 * A hash of the encrypted data is generated for integrity-check and is prefixed to the encrypted_text.
 *
 * Instruction (no secret key provided as argument, so private static value is used):
 * encryption:  $encrypted_txt    = Cryptor::doEncrypt($plain_txt);
 * decryption:  $plain_txt        = Cryptor::doDecrypt($encrypted_txt);
 *
 * Instruction (with secret key):
 * encryption:  $encrypted_txt    = Cryptor::doEncrypt($plain_txt, "secret key used for encryption");
 * decryption:  $plain_txt        = Cryptor::doDecrypt($encrypted_txt, "secret key used for encryption");
 *
 * Change class properties (change secret keys, etc)!
 *
 *************************************************************************************************************************************************/
class Cryptor {
    /**
     * PHP Encryption and decryption class :: open_ssl
     * 
     * public gist: 
     * https://gist.github.com/petermuller71/33616d55174d9725fc00a663d30194ba
     *
     * @param      string       $plain_txt        Text, to be encrypted
     * @param      string       $encrypted_txt    Text, to be decrypted
     * @param      string       $secret_key       Optional, override with (static private) property 
     * 
     * @property   int          $strspit_nr       Amount of characters to split source (<= 400!), open_ssl cannot encrypt large files
     * @property   string       $rep_letter       Letter used to replace underscore (prevent detecting str_splits)
     * @property   string       $secret_key       Secret_key (sha512 hashvalue is created from this string), used if secret_key is not passed as argument
     * 
     * @return     string       Encrypted or decrypted text
     *
     * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
     * @copyright  2018 Peter Muller. All rights reserved.
     * @author     Peter Muller <petermuller71@gmail.com>
     * @version    1.08
     *
     */
	 
	 
	private $CI;
    private $data;

    public function __construct ()
    {
        $this->CI = & get_instance ();
    }
    
	
	
	static private $secret_key = 'travai_secret_key'; // defined secret key  
	static private $secret_iv = 'travai_secret_iv'; // defined secret iv key 
	static private $encrypt_method = "AES-256-CBC"; // defined encrypt method  
    
    
	
	 /*
     * doEncrypt
     * Encrypt text
     *
     * @param   string    $string   Text that will be encrypted
     *
     */
	public static function doEncrypt($string){
	$output = false;
	$key = hash( 'sha256', self::$secret_key );
	$iv = substr( hash( 'sha256', self::$secret_iv ), 0, 16 );
	$output = base64_encode( openssl_encrypt( $string, self::$encrypt_method, $key, 0, $iv ) );   
    return $output;
	
	}

	 /*
     * doDecrypt
     * Decrypt text
     *
     * @param   string    $string   Text that will be decrypted
     *
     */
	public static function doDecrypt($string){	
		$output = false;
		$key = hash( 'sha256', self::$secret_key );
		$iv = substr( hash( 'sha256', self::$secret_iv ), 0, 16 );
		$output = openssl_decrypt( base64_decode( $string ), self::$encrypt_method, $key, 0, $iv );    
		return $output;
		
	}
    
}


?>