<?php
/**
 * Plugin Name: Cookies GDPR
 * Plugin URI: https://wordpress.org/plugins/cookies-gdpr/
 * Description: Simply add content and display cookies popup for GDPR compliance.
 * Version: 1.009
 * Author: Sirius Pro
 * Author URI: https://siriuspro.pl
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

$plugin_data = get_file_data(__FILE__, ['Version' => 'Version'], 'plugin');
$cookies_gdpr_ver = $plugin_data['Version'];


function cookies_gdpr_enquene_files() {
    global $cookies_gdpr_ver;

    wp_enqueue_style( 'cookies-gdpr-style', plugins_url( 'assets/style.css', __FILE__ ), array(), $cookies_gdpr_ver );

    wp_register_script( 'cookies-gdpr-script', plugins_url( 'assets/script.js', __FILE__ ), array(), $cookies_gdpr_ver );

    wp_enqueue_script( 'cookies-gdpr-script' );


}
add_action( 'wp_enqueue_scripts', 'cookies_gdpr_enquene_files' );

function cookies_gdpr_scripts_async($tag){
    $scripts_to_async = array('cookies-gdpr/assets/script.js');
    foreach($scripts_to_async as $async_script){
        if(true == strpos($tag, $async_script ) )
            return str_replace( ' src', ' defer src', $tag );	
    }
    return $tag;
}

add_filter( 'script_loader_tag', 'cookies_gdpr_scripts_async', 10 );

function cookies_gdpr_style_async($tag){
    $styles_to_async = array('cookies-gdpr-style');
    foreach($styles_to_async as $async_style){
        if(true == strpos($tag, $async_style ) )
            return str_replace( ' href', '  media="print" onload="this.media=\'all\'; this.onload=null;" href', $tag );	
    }
    return $tag;
}

add_filter( 'style_loader_tag', 'cookies_gdpr_style_async', 10 );


function cookies_gdpr_settings() {
    register_setting( 'cookies_gdpr_options', 'cookies_gdpr_content' );
    register_setting( 'cookies_gdpr_options', 'cookies_gdpr_consent' );
    
    add_settings_section(
        'cookies_gdpr_settings',
        '',
        'cookies_gdpr_settings_callback',
        'cookies_gdpr_options'
    );
    add_settings_field(
        'cookies_gdpr_content',
        'Cookies message',
        'cookies_gdpr_content_callback',
        'cookies_gdpr_options',
        'cookies_gdpr_settings'
    );
    add_settings_field(
        'cookies_gdpr_consent',
        'Button text',
        'cookies_gdpr_consent_callback',
        'cookies_gdpr_options',
        'cookies_gdpr_settings'
    );
}
add_action( 'admin_init', 'cookies_gdpr_settings' );

function cookies_gdpr_settings_callback( $args ) {
?>
<?php
}

function cookies_gdpr_options_page()
{
    add_menu_page( 
    'Cookies GDPR',
    'Cookies GDPR',
    'manage_options',
    'cookies_gdpr_options',
    'cookies_gdpr_options_html',
		'dashicons-yes-alt',
		'1'
    );
}
add_action('admin_menu', 'cookies_gdpr_options_page');

function cookies_gdpr_options_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    global $wpdb;
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
        <?php
    settings_fields( 'cookies_gdpr_options' );
    do_settings_sections( 'cookies_gdpr_options' );
    submit_button( __( 'Save Changes', 'text-domain' ) );
        ?>
    </form>
</div>
<?php
}

function cookies_gdpr_content_callback ($args){
    $setting = get_option( 'cookies_gdpr_content' );
?>
<input type="text"
       name="cookies_gdpr_content"
       value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"
       >
<?php
}

function cookies_gdpr_consent_callback ($args){
    $setting = get_option( 'cookies_gdpr_consent' );
?>
<input type="text"
       name="cookies_gdpr_consent"
       value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"
       >
<?php
}

function cookies_gdpr_popup() {
  if ( ! isset( $_COOKIE['cookies_gdpr_consent'] ) ) {
    $cookies_gdpr_content = 'We use cookies to provide you with the best possible experience on our website. By clicking "Accept All Cookies," you agree to our use of cookies. You can change your cookie settings at any time in your browser settings. For more information about how we use cookies, please see our Privacy Policy';
    $cookies_gdpr_button = 'I accept';
    if ( get_option( 'cookies_gdpr_content' ) ) {
      $cookies_gdpr_content = get_option( 'cookies_gdpr_content' );
    } 
    if ( get_option( 'cookies_gdpr_consent' ) ) {
      $cookies_gdpr_button = get_option( 'cookies_gdpr_consent' );
    }
  ?>
    <div class="cookies-gdpr" id="cookies-gdpr-modal">
      <div class="cookies-gdpr__flex">
        <div class="cookies-gdpr__content">
          <p class="cookies-gdpr__text"><?php echo esc_html( $cookies_gdpr_content ); ?></p>
          <button class="cookies-gdpr__button" id="cookies-gdpr-button"><?php echo esc_html( $cookies_gdpr_button ); ?></button>
        </div>
      </div>
    </div>
    <?php
  }
}
add_action( 'wp_footer', 'cookies_gdpr_popup' );