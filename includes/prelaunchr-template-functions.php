<?php
/**
 * Prelaunchr Template Hooks
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Prelaunchr wrapping divs
 */
add_action( 'prelaunchr_before_form', 'prelaunchr_wrapper_start', 10 );
add_action( 'prelaunchr_after_form', 'prelaunchr_wrapper_end', 10 );
add_action( 'prelaunchr_before_thankyou', 'prelaunchr_wrapper_start', 10 );
add_action( 'prelaunchr_after_thankyou', 'prelaunchr_wrapper_end', 10 );

/**
 * Form response / validation messages
 */
add_action( 'prelaunchr_before_form', 'prelaunchr_response', 20 );

/**
 * prelaunchr_form hooks
 */
add_action( 'prelaunchr_form', 'prelaunchr_input_honeypot', 10 );
add_action( 'prelaunchr_form', 'prelaunchr_input_email', 20 );
add_action( 'prelaunchr_form', 'prelaunchr_button_submit', 30 );

/**
 * prelaunchr_thankyou hooks
 */
add_action( 'prelaunchr_thankyou', 'prelaunchr_thankyou_intro', 10 );
add_action( 'prelaunchr_thankyou', 'prelaunchr_referral_link', 20 );
add_action( 'prelaunchr_thankyou', 'prelaunchr_social_share', 30 );
add_action( 'prelaunchr_thankyou', 'prelaunchr_referral_progress', 40 );



if ( ! function_exists( 'prelaunchr_wrapper_start' ) ) {

	function prelaunchr_wrapper_start() {
		Prelaunchr()->prelaunchr_get_template_part( 'global/wrapper-start' );
	}

}

if ( ! function_exists( 'prelaunchr_wrapper_end' ) ) {

	function prelaunchr_wrapper_end() {
		Prelaunchr()->prelaunchr_get_template_part( 'global/wrapper-end' );
	}

}

if ( ! function_exists( 'prelaunchr_response' ) ) {

	function prelaunchr_response() {
		Prelaunchr()->prelaunchr_get_template_part( 'form/response' );
	}

}

if ( ! function_exists( 'prelaunchr_input_honeypot' ) ) {

	function prelaunchr_input_honeypot() {
		Prelaunchr()->prelaunchr_get_template_part( 'form/honeypot' );
	}

}

if ( ! function_exists( 'prelaunchr_input_email' ) ) {

	function prelaunchr_input_email() {
		Prelaunchr()->prelaunchr_get_template_part( 'form/email' );
	}

}

if ( ! function_exists( 'prelaunchr_button_submit' ) ) {

	function prelaunchr_button_submit() {
		Prelaunchr()->prelaunchr_get_template_part( 'form/submit' );
	}

}

if ( ! function_exists( 'prelaunchr_thankyou_intro' ) ) {

	function prelaunchr_thankyou_intro() {
		Prelaunchr()->prelaunchr_get_template_part( 'thankyou/intro' );
	}

}

if ( ! function_exists( 'prelaunchr_referral_link' ) ) {

	function prelaunchr_referral_link() {
		Prelaunchr()->prelaunchr_get_template_part( 'thankyou/referral-link' );
	}

}

if ( ! function_exists( 'prelaunchr_social_share' ) ) {

	function prelaunchr_social_share() {
		Prelaunchr()->prelaunchr_get_template_part( 'thankyou/share' );
	}

}

if ( ! function_exists( 'prelaunchr_referral_progress' ) ) {

	function prelaunchr_referral_progress() {
		Prelaunchr()->prelaunchr_get_template_part( 'thankyou/referral-progress' );
	}

}

if ( ! function_exists( 'prelaunchr_thankyou_text' ) ) {

	function prelaunchr_thankyou_text() {

		$text = apply_filters( 'the_content', get_option( 'prelaunchr-core-thankyou-text' ) );

		echo $text;

	}

}
