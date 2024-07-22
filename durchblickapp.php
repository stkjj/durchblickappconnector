<?php
/*
Plugin Name: Durchblick Connector
Description: Establishes the connction the <a href="https://durchblick.app/">Durchblick App Dashboard</a> and inserts the JavaScript Code for the Feedback Widget into the &lt;head&gt;.
Version: 1.3
Author: KeDe Digital LLP
Text Domain: durchblick-connector
Domain Path: /languages
GitHub Plugin URI: https://github.com/stkjj/durchblickappconnector
Primary Branch: main
*/

// Define default options
function durchblick_connector_get_default_options() {
    return array(
        'apiKey' => '',
//         'customButtonSelector' => '',
        'publicFeedback' => false,
        'primaryColor' => '#333',
        'secondaryColor' => '#ccc',
        'dotColor' => '#f00',
        'dotSize' => '20px',
//         'borderRadius' => '6px',
        'enterFeedbackModeText' => 'Feedback Mode Off',
        'exitFeedbackModeText' => 'Feedback Mode On',
//         'feedbackPlaceholder' => 'Your feedback',
//         'emailPlaceholder' => 'Your email',
//         'verificationCodePlaceholder' => 'Verification code',
//         'verificationCodeMessage' => 'Please enter the one-time verification code sent to your email.',
//         'submitButtonText' => 'Submit',
//         'cancelButtonText' => 'Cancel',
//         'feedbackThankYouMessage' => 'Thank you for your feedback!',
//         'feedbackLoadingMessage' => 'Loading...',
//         'feedbackErrorMessage' => 'An error occurred while sending your feedback. Please try again later.',
//         'feedbackFirstTimeMessage' => 'Welcome to Feedback Mode! Click anywhere to give feedback.',
//         'requireEmailVerification' => true,
//         'stayInFeedbackMode' => false
    );
}

// Initialize plugin options
function durchblick_connector_init_options() {
    if (false === get_option('durchblick_connector_options')) {
        add_option('durchblick_connector_options', durchblick_connector_get_default_options());
    }
}
add_action('admin_init', 'durchblick_connector_init_options');

// Add options page
function durchblick_connector_add_admin_menu() {
    add_options_page(
        __('Durchblick Connector Settings', 'durchblick-connector'),
        __('Durchblick Connector', 'durchblick-connector'),
        'manage_options',
        'durchblick-connector',
        'durchblick_connector_options_page'
    );
}
add_action('admin_menu', 'durchblick_connector_add_admin_menu');

// Render options page
function durchblick_connector_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Durchblick Connector Settings', 'durchblick-connector'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('durchblick_connector_options_group');
            do_settings_sections('durchblick-connector');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function durchblick_connector_settings_init() {
    register_setting('durchblick_connector_options_group', 'durchblick_connector_options', 'durchblick_connector_options_validate');

    add_settings_section(
        'durchblick_connector_settings_section',
        __('Connector Settings', 'durchblick-connector'),
        'durchblick_connector_settings_section_callback',
        'durchblick-connector'
    );

    $fields = array(
        'apiKey' => array('label' => __('API Key', 'durchblick-connector'), 'type' => 'text'),
//         'customButtonSelector' => array('label' => __('Custom Button Selector', 'durchblick-connector'), 'type' => 'text'),
        'publicFeedback' => array('label' => __('Public Feedback', 'durchblick-connector'), 'type' => 'checkbox'),
        'primaryColor' => array('label' => __('Primary Color', 'durchblick-connector'), 'type' => 'color'),
        'secondaryColor' => array('label' => __('Secondary Color', 'durchblick-connector'), 'type' => 'color'),
        'dotColor' => array('label' => __('Dot Color', 'durchblick-connector'), 'type' => 'color'),
        'dotSize' => array('label' => __('Dot Size', 'durchblick-connector'), 'type' => 'text'),
//         'borderRadius' => array('label' => __('Border Radius', 'durchblick-connector'), 'type' => 'text'),
        'enterFeedbackModeText' => array('label' => __('Enter Feedback Mode Text', 'durchblick-connector'), 'type' => 'text'),
        'exitFeedbackModeText' => array('label' => __('Exit Feedback Mode Text', 'durchblick-connector'), 'type' => 'text'),
//         'feedbackPlaceholder' => array('label' => __('Feedback Placeholder', 'durchblick-connector'), 'type' => 'text'),
//         'emailPlaceholder' => array('label' => __('Email Placeholder', 'durchblick-connector'), 'type' => 'text'),
//         'verificationCodePlaceholder' => array('label' => __('Verification Code Placeholder', 'durchblick-connector'), 'type' => 'text'),
//         'verificationCodeMessage' => array('label' => __('Verification Code Message', 'durchblick-connector'), 'type' => 'text'),
//         'submitButtonText' => array('label' => __('Submit Button Text', 'durchblick-connector'), 'type' => 'text'),
//         'cancelButtonText' => array('label' => __('Cancel Button Text', 'durchblick-connector'), 'type' => 'text'),
//         'feedbackThankYouMessage' => array('label' => __('Feedback Thank You Message', 'durchblick-connector'), 'type' => 'text'),
//         'feedbackLoadingMessage' => array('label' => __('Feedback Loading Message', 'durchblick-connector'), 'type' => 'text'),
//         'feedbackErrorMessage' => array('label' => __('Feedback Error Message', 'durchblick-connector'), 'type' => 'text'),
//         'feedbackFirstTimeMessage' => array('label' => __('Feedback First Time Message', 'durchblick-connector'), 'type' => 'text'),
//         'requireEmailVerification' => array('label' => __('Require Email Verification', 'durchblick-connector'), 'type' => 'checkbox'),
//         'stayInFeedbackMode' => array('label' => __('Stay In Feedback Mode', 'durchblick-connector'), 'type' => 'checkbox')
    );

    foreach ($fields as $field => $data) {
        add_settings_field(
            $field,
            $data['label'],
            'durchblick_connector_render_field',
            'durchblick-connector',
            'durchblick_connector_settings_section',
            array(
                'field' => $field,
                'type' => $data['type']
            )
        );
    }
}
add_action('admin_init', 'durchblick_connector_settings_init');

// Render fields
function durchblick_connector_render_field($args) {
    $options = get_option('durchblick_connector_options');
    $field = $args['field'];
    $type = $args['type'];
    $value = isset($options[$field]) ? esc_attr($options[$field]) : '';
    
    switch ($type) {
        case 'text':
            echo "<input type='text' name='durchblick_connector_options[$field]' value='$value'>";
            break;
        case 'checkbox':
            $checked = $value ? 'checked' : '';
            echo "<input type='checkbox' name='durchblick_connector_options[$field]' value='1' $checked>";
            break;
        case 'color':
            echo "<input type='text' class='color-field' name='durchblick_connector_options[$field]' value='$value'>";
            break;
    }
}

// Enqueue color picker scripts and styles
function durchblick_connector_enqueue_color_picker($hook_suffix) {
    if ('settings_page_durchblick-connector' !== $hook_suffix) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('durchblick_connector_color_picker', plugins_url('color-picker.js', __FILE__), array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'durchblick_connector_enqueue_color_picker');

// Section callback
function durchblick_connector_settings_section_callback() {
    echo __('Configure the settings for the Durchblick Connector.', 'durchblick-connector');
}

// Validate options
function durchblick_connector_options_validate($input) {
    $input['apiKey'] = sanitize_text_field($input['apiKey']);
//     $input['customButtonSelector'] = sanitize_text_field($input['customButtonSelector']);
    $input['primaryColor'] = sanitize_text_field($input['primaryColor']);
    $input['secondaryColor'] = sanitize_text_field($input['secondaryColor']);
    $input['dotColor'] = sanitize_text_field($input['dotColor']);
    $input['dotSize'] = sanitize_text_field($input['dotSize']);
//     $input['borderRadius'] = sanitize_text_field($input['borderRadius']);
    $input['enterFeedbackModeText'] = sanitize_text_field($input['enterFeedbackModeText']);
    $input['exitFeedbackModeText'] = sanitize_text_field($input['exitFeedbackModeText']);
//     $input['feedbackPlaceholder'] = sanitize_text_field($input['feedbackPlaceholder']);
//     $input['emailPlaceholder'] = sanitize_text_field($input['emailPlaceholder']);
//     $input['verificationCodePlaceholder'] = sanitize_text_field($input['verificationCodePlaceholder']);
//     $input['verificationCodeMessage'] = sanitize_text_field($input['verificationCodeMessage']);
//     $input['submitButtonText'] = sanitize_text_field($input['submitButtonText']);
//     $input['cancelButtonText'] = sanitize_text_field($input['cancelButtonText']);
//     $input['feedbackThankYouMessage'] = sanitize_text_field($input['feedbackThankYouMessage']);
//     $input['feedbackLoadingMessage'] = sanitize_text_field($input['feedbackLoadingMessage']);
//     $input['feedbackErrorMessage'] = sanitize_text_field($input['feedbackErrorMessage']);
//     $input['feedbackFirstTimeMessage'] = sanitize_text_field($input['feedbackFirstTimeMessage']);
//     $input['requireEmailVerification'] = isset($input['requireEmailVerification']) ? 1 : 0;
//     $input['stayInFeedbackMode'] = isset($input['stayInFeedbackMode']) ? 1 : 0;
    return $input;
}

// Enqueue JavaScript in the header
function durchblick_connector_enqueue_script() {
    $options = get_option('durchblick_connector_options');
    ?>
    <script src="https://durchblick.app/widget.js"></script>
    <script>
        DurchblickWidget.init({
            apiKey: '<?php echo esc_js($options['apiKey']); ?>',
//             customButtonSelector: <?php echo $options['customButtonSelector'] ? "'". esc_js($options['customButtonSelector']) . "'" : 'null'; ?>,
            publicFeedback: <?php echo $options['publicFeedback'] ? 'true' : 'false'; ?>,
            primaryColor: '<?php echo esc_js($options['primaryColor']); ?>',
            secondaryColor: '<?php echo esc_js($options['secondaryColor']); ?>',
            dotSize: '<?php echo esc_js($options['dotSize']); ?>',
            dotColor: '<?php echo esc_js($options['dotColor']); ?>',
//             borderRadius: '<?php echo esc_js($options['borderRadius']); ?>',
            enterFeedbackModeText: '<?php echo esc_js($options['enterFeedbackModeText']); ?>',
            exitFeedbackModeText: '<?php echo esc_js($options['exitFeedbackModeText']); ?>',
//             feedbackPlaceholder: '<?php echo esc_js($options['feedbackPlaceholder']); ?>',
//             emailPlaceholder: '<?php echo esc_js($options['emailPlaceholder']); ?>',
//             verificationCodePlaceholder: '<?php echo esc_js($options['verificationCodePlaceholder']); ?>',
//             verificationCodeMessage: '<?php echo esc_js($options['verificationCodeMessage']); ?>',
//             submitButtonText: '<?php echo esc_js($options['submitButtonText']); ?>',
//             cancelButtonText: '<?php echo esc_js($options['cancelButtonText']); ?>',
//             feedbackThankYouMessage: '<?php echo esc_js($options['feedbackThankYouMessage']); ?>',
//             feedbackLoadingMessage: '<?php echo esc_js($options['feedbackLoadingMessage']); ?>',
//             feedbackErrorMessage: '<?php echo esc_js($options['feedbackErrorMessage']); ?>',
//             feedbackFirstTimeMessage: '<?php echo esc_js($options['feedbackFirstTimeMessage']); ?>',
//             requireEmailVerification: <?php echo $options['requireEmailVerification'] ? 'true' : 'false'; ?>,
//             stayInFeedbackMode: <?php echo $options['stayInFeedbackMode'] ? 'true' : 'false'; ?>
        });
    </script>
    <?php
}
add_action('wp_head', 'durchblick_connector_enqueue_script');

// Load text domain
function durchblick_connector_load_textdomain() {
    load_plugin_textdomain('durchblick-connector', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'durchblick_connector_load_textdomain');
