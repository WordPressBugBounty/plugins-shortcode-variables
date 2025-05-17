<?php

defined('ABSPATH') or die("Jog on!");

/**
 * Get a user's ID
 *
 * Class SV_SC_USER_IP
 */
class SV_SC_USER_IP extends SV_Preset {

	protected function unsanitised() {

		// Code based on WP Beginner article: http://www.wpbeginner.com/wp-tutorials/how-to-display-a-users-ip-address-in-wordpress/
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;

	}
}

/**
 * Today's date
 *
 * Class SV_SC_TODAYS_DATE
 */
class SV_SC_TODAYS_DATE extends SV_Preset {

	protected function unsanitised() {

		$args = $this->get_arguments();

		$date_format = ( false === empty( $args['format'] ) ) ? $args['format'] : 'd/m/Y';

		return date( $date_format );

	}
}

/**
 * User Agent
 *
 * Class SV_SC_USER_AGENT
 */
class SV_SC_USER_AGENT extends SV_Preset {
	protected function unsanitised() {
		return $_SERVER['HTTP_USER_AGENT'];
	}
}

/**
 * Page title
 *
 * Class SV_SC_PAGE_TITLE
 */
class SV_SC_PAGE_TITLE extends SV_Preset {

	protected function unsanitised() {
		return the_title('', '', false);
	}
}

/**
 * Login Page
 *
 * Class SV_SC_LOGIN_PAGE
 */
class SV_SC_LOGIN_PAGE extends SV_Preset {

	public function init() {
		$this->escape_method = 'esc_url_raw';
	}

	protected function unsanitised() {

		$args = $this->get_arguments();

		$redirect = ( false === empty( $args['redirect'] ) ) ? $args['redirect'] : '';

		return wp_login_url( $redirect );
	}
}
/**
 * Policy URL
 *
 * Class SV_SC_POLICY_URL
 */
class SV_SC_POLICY_URL extends SV_Preset {

	public function init() {
		$this->escape_method = 'esc_url_raw';
	}

	protected function unsanitised() {
		return get_privacy_policy_url();
	}
}

/**
 * User Info
 *
 * Class SV_USER_INFO
 */
class SV_SC_USER_INFO extends SV_Preset {

	protected function unsanitised() {

		$args = $this->get_arguments();

		$key = ( false === empty( $args['_sh_cd_func'] ) ) ? $args['_sh_cd_func'] : 'ID';

		$current_user = wp_get_current_user();

		// Not logged in?
		if ( false === $current_user->exists() ) {
			return '';
		}

		switch ( $key ) {

			case 'user_login':
				return  $current_user->user_login;
				break;
			case 'user_email':
				return $current_user->user_email;
				break;
			case 'user_firstname':
				return $current_user->user_firstname;
				break;
			case 'user_lastname':
				return $current_user->user_lastname;
				break;
			case 'display_name':
				return $current_user->display_name;
				break;
			default:
				return $current_user->ID;

		}

		return '';
	}
}
