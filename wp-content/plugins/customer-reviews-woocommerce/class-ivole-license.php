<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ivole_License' ) ) :

  require_once('class-ivole-email.php');

	class Ivole_License {
	  public function __construct() {
	  }

		public function check_license() {
      $licenseKey = get_option( 'ivole_license_key', '' );
      if( strlen( $licenseKey ) === 0 ) {
        return __( 'No license key entered' );
      }
      $data = array(
				'token' => '164592f60fbf658711d47b2f55a1bbba',
				'licenseKey' => $licenseKey,
        'shopDomain' => Ivole_Email::get_blogurl()
			);
			$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/check-license';
      $data_string = json_encode($data);
      $ch = curl_init();
  		curl_setopt( $ch, CURLOPT_URL, $api_url );
  		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Content-Length: ' . strlen( $data_string ) )
  		);
  		$result = curl_exec( $ch );
      if( false === $result ) {
  			return __( 'Unknown: ', 'ivole' ) . curl_error( $ch );
  		}
      $result = json_decode( $result );
      if( isset( $result->error ) ) {
				update_option( 'ivole_reviews_nobranding', 'no' );
        return __( 'Not Active: ', 'ivole' ) . $result->error;
      } else if( isset( $result->valid ) && 1 == $result->valid ) {
        return __( 'Active', 'ivole' );
      } else if( isset( $result->expired ) && 1 == $result->expired ) {
				update_option( 'ivole_reviews_nobranding', 'no' );
        return __( 'Expired', 'ivole' );
      } else {
				update_option( 'ivole_reviews_nobranding', 'no' );
        return __( 'Unknown Error', 'ivole' );
      }
		}

    public function register_license( $new_license ) {
      if( strlen( $new_license ) === 0 ) {
        return;
      }
      $data = array(
				'token' => '164592f60fbf658711d47b2f55a1bbba',
				'licenseKey' => $new_license,
        'shopDomain' => Ivole_Email::get_blogurl()
			);
      $api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/register-license';
      $data_string = json_encode($data);
      $ch = curl_init();
  		curl_setopt( $ch, CURLOPT_URL, $api_url );
  		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Content-Length: ' . strlen( $data_string ) )
  		);
  		$result = curl_exec( $ch );
      if( false === $result ) {
        WC_Admin_Settings::add_error( sprintf( __( 'License registration error: %s.', 'ivole' ), curl_error( $ch ) ) );
  			return;
  		}
      $result = json_decode( $result );
      //error_log( print_r( $result, true ) );
      if( isset( $result->status ) ) {
        WC_Admin_Settings::add_message( sprintf( __( 'License has been successfully registered for \'%s\'.', 'ivole' ), Ivole_Email::get_blogurl() ) );
        return;
      } else if( isset( $result->error ) ) {
        WC_Admin_Settings::add_error( sprintf( __( 'License registration error: %s.', 'ivole' ), $result->error ) );
        return;
      } else {
        WC_Admin_Settings::add_error( __( 'License registration error #99', 'ivole' ) );
        return;
      }
    }

	}

endif;

?>
