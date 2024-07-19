<?php
/*
Plugin Name: Durchblick Connector
Description: Establishes the connction the <a href="https://durchblick.app/">Durchblick App Dashboard</a> and inserts the JavaScript Code for the Feedback Widget into the &lt;head&gt;.
Version: 1.2
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
        'publicFeedback' => false,
        'primaryColor' => '#333',
        'secondaryColor' => '#ccc',
        'dotColor' => '#f00',
        'dotSize' => '20px',
        'enterFeedbackModeText' => 'Feedback Mode Off',
        'exitFeedbackModeText' => 'Feedback Mode On'
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

    add_settings_field(
        'apiKey',
        __('API Key', 'durchblick-connector'),
        'durchblick_connector_apiKey_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'publicFeedback',
        __('Allow Feedback', 'durchblick-connector'),
        'durchblick_connector_publicFeedback_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'primaryColor',
        __('Primary Color', 'durchblick-connector'),
        'durchblick_connector_primaryColor_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'secondaryColor',
        __('Secondary Color', 'durchblick-connector'),
        'durchblick_connector_secondaryColor_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'dotColor',
        __('Dot Color', 'durchblick-connector'),
        'durchblick_connector_dotColor_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'dotSize',
        __('Dot Size', 'durchblick-connector'),
        'durchblick_connector_dotSize_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'enterFeedbackModeText',
        __('Enter Feedback Mode Text', 'durchblick-connector'),
        'durchblick_connector_enterFeedbackModeText_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );

    add_settings_field(
        'exitFeedbackModeText',
        __('Exit Feedback Mode Text', 'durchblick-connector'),
        'durchblick_connector_exitFeedbackModeText_render',
        'durchblick-connector',
        'durchblick_connector_settings_section'
    );
}
add_action('admin_init', 'durchblick_connector_settings_init');

// Enqueue color picker scripts and styles
function durchblick_connector_enqueue_color_picker($hook_suffix) {
    if ('settings_page_durchblick-connector' !== $hook_suffix) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('durchblick_connector_color_picker', plugins_url('color-picker.js', __FILE__), array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'durchblick_connector_enqueue_color_picker');

// Render fields
function durchblick_connector_apiKey_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' name='durchblick_connector_options[apiKey]' value='<?php echo $options['apiKey']; ?>'>
    <?php
}

function durchblick_connector_publicFeedback_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='checkbox' name='durchblick_connector_options[publicFeedback]' <?php checked($options['publicFeedback'], 1); ?> value='1'>
    <?php
}

function durchblick_connector_primaryColor_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' class='color-field' name='durchblick_connector_options[primaryColor]' value='<?php echo $options['primaryColor']; ?>'>
    <?php
}

function durchblick_connector_secondaryColor_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' class='color-field' name='durchblick_connector_options[secondaryColor]' value='<?php echo $options['secondaryColor']; ?>'>
    <?php
}

function durchblick_connector_dotColor_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' class='color-field' name='durchblick_connector_options[dotColor]' value='<?php echo $options['dotColor']; ?>'>
    <?php
}

function durchblick_connector_dotSize_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' name='durchblick_connector_options[dotSize]' value='<?php echo $options['dotSize']; ?>'>
    <?php
}

function durchblick_connector_enterFeedbackModeText_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' name='durchblick_connector_options[enterFeedbackModeText]' value='<?php echo $options['enterFeedbackModeText']; ?>'>
    <?php
}

function durchblick_connector_exitFeedbackModeText_render() {
    $options = get_option('durchblick_connector_options');
    ?>
    <input type='text' name='durchblick_connector_options[exitFeedbackModeText]' value='<?php echo $options['exitFeedbackModeText']; ?>'>
    <?php
}

// Section callback
function durchblick_connector_settings_section_callback() {
    echo __('Configure the settings for the Durchblick Connector.', 'durchblick-connector');
}

// Validate options
function durchblick_connector_options_validate($input) {
    $input['apiKey'] = sanitize_text_field($input['apiKey']);
    $input['primaryColor'] = sanitize_text_field($input['primaryColor']);
    $input['secondaryColor'] = sanitize_text_field($input['secondaryColor']);
    $input['dotColor'] = sanitize_text_field($input['dotColor']);
    $input['dotSize'] = sanitize_text_field($input['dotSize']);
    $input['enterFeedbackModeText'] = sanitize_text_field($input['enterFeedbackModeText']);
    $input['exitFeedbackModeText'] = sanitize_text_field($input['exitFeedbackModeText']);
    $input['publicFeedback'] = isset($input['publicFeedback']) ? 1 : 0;
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
            publicFeedback: <?php echo $options['publicFeedback'] ? 'true' : 'false'; ?>,
            primaryColor: '<?php echo esc_js($options['primaryColor']); ?>',
            secondaryColor: '<?php echo esc_js($options['secondaryColor']); ?>',
            dotSize: '<?php echo esc_js($options['dotSize']); ?>',
            dotColor: '<?php echo esc_js($options['dotColor']); ?>',
            enterFeedbackModeText: '<?php echo esc_js($options['enterFeedbackModeText']); ?>',
            exitFeedbackModeText: '<?php echo esc_js($options['exitFeedbackModeText']); ?>'
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
