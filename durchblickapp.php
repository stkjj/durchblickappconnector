<?php
/*
Plugin Name: Durchblick Connector
Description: Fügt den Durchblick App Code vor dem schließenden </head> Tag ein.
Version: 1.1
Author: KeDe Digital
GitHub Plugin URI: https://github.com/stkjj/durchblickappconnector
Primary Branch: main
*/

<?php

// Standardwerte definieren
function durchblick_widget_get_default_options() {
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

// Plugin-Optionen initialisieren
function durchblick_widget_init_options() {
    if (false === get_option('durchblick_widget_options')) {
        add_option('durchblick_widget_options', durchblick_widget_get_default_options());
    }
}
add_action('admin_init', 'durchblick_widget_init_options');

// Einstellungsseite hinzufügen
function durchblick_widget_add_admin_menu() {
    add_options_page(
        'Durchblick Widget Einstellungen',
        'Durchblick Widget',
        'manage_options',
        'durchblick-widget',
        'durchblick_widget_options_page'
    );
}
add_action('admin_menu', 'durchblick_widget_add_admin_menu');

// Einstellungsseite rendern
function durchblick_widget_options_page() {
    ?>
    <div class="wrap">
        <h1>Durchblick Widget Einstellungen</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('durchblick_widget_options_group');
            do_settings_sections('durchblick-widget');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Einstellungen und Felder registrieren
function durchblick_widget_settings_init() {
    register_setting('durchblick_widget_options_group', 'durchblick_widget_options', 'durchblick_widget_options_validate');

    add_settings_section(
        'durchblick_widget_settings_section',
        'Widget Einstellungen',
        'durchblick_widget_settings_section_callback',
        'durchblick-widget'
    );

    add_settings_field(
        'apiKey',
        'API-Key',
        'durchblick_widget_apiKey_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'publicFeedback',
        'Öffentliches Feedback',
        'durchblick_widget_publicFeedback_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'primaryColor',
        'Primärfarbe',
        'durchblick_widget_primaryColor_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'secondaryColor',
        'Sekundärfarbe',
        'durchblick_widget_secondaryColor_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'dotColor',
        'Punktfarbe',
        'durchblick_widget_dotColor_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'dotSize',
        'Punktgröße',
        'durchblick_widget_dotSize_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'enterFeedbackModeText',
        'Text für Feedback-Modus starten',
        'durchblick_widget_enterFeedbackModeText_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );

    add_settings_field(
        'exitFeedbackModeText',
        'Text für Feedback-Modus beenden',
        'durchblick_widget_exitFeedbackModeText_render',
        'durchblick-widget',
        'durchblick_widget_settings_section'
    );
}
add_action('admin_init', 'durchblick_widget_settings_init');

// Felder rendern
function durchblick_widget_apiKey_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[apiKey]' value='<?php echo $options['apiKey']; ?>'>
    <?php
}

function durchblick_widget_publicFeedback_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='checkbox' name='durchblick_widget_options[publicFeedback]' <?php checked($options['publicFeedback'], 1); ?> value='1'>
    <?php
}

function durchblick_widget_primaryColor_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[primaryColor]' value='<?php echo $options['primaryColor']; ?>'>
    <?php
}

function durchblick_widget_secondaryColor_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[secondaryColor]' value='<?php echo $options['secondaryColor']; ?>'>
    <?php
}

function durchblick_widget_dotColor_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[dotColor]' value='<?php echo $options['dotColor']; ?>'>
    <?php
}

function durchblick_widget_dotSize_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[dotSize]' value='<?php echo $options['dotSize']; ?>'>
    <?php
}

function durchblick_widget_enterFeedbackModeText_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[enterFeedbackModeText]' value='<?php echo $options['enterFeedbackModeText']; ?>'>
    <?php
}

function durchblick_widget_exitFeedbackModeText_render() {
    $options = get_option('durchblick_widget_options');
    ?>
    <input type='text' name='durchblick_widget_options[exitFeedbackModeText]' value='<?php echo $options['exitFeedbackModeText']; ?>'>
    <?php
}

// Sektion Callback
function durchblick_widget_settings_section_callback() {
    echo 'Konfiguriere die Einstellungen für das Durchblick Widget.';
}

// Optionen validieren
function durchblick_widget_options_validate($input) {
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

// JavaScript in den Header einfügen
function durchblick_widget_enqueue_script() {
    $options = get_option('durchblick_widget_options');
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
add_action('wp_head', 'durchblick_widget_enqueue_script');
