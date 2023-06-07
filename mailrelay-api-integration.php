<?php

/**
 * Plugin Name: Mailrelay API Integration
 * Plugin URI: https://pixelinlove.net/
 * Description: Integrates Contact Form 7 with the Mailrelay API.
 * Version: 1.0
 * Author: Pixel in Love
 * Author URI: https://pixelinlove.net/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

function mailrelay_api_integration_settings()
{
  add_options_page('Mailrelay API Integration', 'Mailrelay API Integration', 'manage_options', 'mailrelay-api-integration', 'mailrelay_api_integration_settings_page');
}
add_action('admin_menu', 'mailrelay_api_integration_settings');

function mailrelay_api_integration_settings_page()
{
?>
  <div class="wrap">
    <h1>Mailrelay API Integration Settings</h1>
    <form method="post" action="options.php">
      <?php
      settings_fields('mailrelay_api_integration');
      do_settings_sections('mailrelay_api_integration');
      ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">API Key</th>
          <td><input type="text" name="mailrelay_api_key" value="<?php echo esc_attr(get_option('mailrelay_api_key')); ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row">List ID</th>
          <td><input type="text" name="mailrelay_list_id" value="<?php echo esc_attr(get_option('mailrelay_list_id')); ?>" /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Instance URL</th>
          <td>
            <input type="text" name="mailrelay_instance" value="<?php echo esc_attr(get_option('mailrelay_instance')); ?>" />
            <p class="description">Enter your Mailrelay instance URL (e.g., my-instance.ipzmarketing.com)</p>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
    <div class="mailrelay-disclaimer">
      <p><strong>Important:</strong> In order to make the integration work, your contact forms must include the following fields:</p>
      <ul>
        <li>An email field with the id "your-email"</li>
        <li>A text field with the id "your-name"</li>
        <li>An acceptance field with the id "acceptance"</li>
      </ul>
    </div>
  </div>
<?php
}

function mailrelay_api_integration_register_settings()
{
  register_setting('mailrelay_api_integration', 'mailrelay_api_key');
  register_setting('mailrelay_api_integration', 'mailrelay_list_id');
  register_setting('mailrelay_api_integration', 'mailrelay_instance');
}
add_action('admin_init', 'mailrelay_api_integration_register_settings');

add_action('wpcf7_before_send_mail', 'mailrelay_api_integration');
function mailrelay_api_integration($wpcf7)
{
  $submission = WPCF7_Submission::get_instance();
  $form_id = $wpcf7->id();
  error_log('Contact Form 7 form ID: ' . $form_id);
  if ($submission) {
    $data = $submission->get_posted_data();
    $email = $data['your-email'];
    $name = $data['your-name'];
    $acceptance = $data['acceptance'];
    if ($acceptance == 1) {
      $api_key = get_option('mailrelay_api_key');
      $list_id = get_option('mailrelay_list_id');
      $instance = get_option('mailrelay_instance');
      $url = 'https://' . $instance . '/api/v1/subscribers';
      $data = array(
        'status' => 'active',
        'email' => $email,
        'name' => $name,
        'group_ids' => array($list_id),
      );
      $args = array(
        'body' => json_encode($data),
        'headers' => array(
          'Content-Type' => 'application/json',
          'X-Auth-Token' => $api_key,
        ),
      );
      $response = wp_remote_post($url, $args);
    }
  }
}
?>