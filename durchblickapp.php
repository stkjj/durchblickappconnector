<?php
/*
Plugin Name: Durchblick Connector
Description: Fügt den Durchblick App Code vor dem schließenden </head> Tag ein.
Version: 1.0
Author: KeDe Digital
GitHub Plugin URI: https://github.com/stkjj/durchblickappconnector
*/

function durchblick_widget_enqueue_script() {
    ?>
    <script src="https://durchblick.app/widget.js"></script>
    <script>
        DurchblickWidget.init({
            apiKey: '5625116251f37381cbda0341',                 // required
            publicFeedback: false,                      // optional, default: false (add ?allowFeedback=1 to the URL to enable)
            primaryColor: '#333',                       // optional, default: #333
            secondaryColor: '#ccc',                     // optional, default: #ccc
            dotSize: '20px',                            // optional, default: 20px
            dotColor: '#f00',                           // optional, default: red
            enterFeedbackModeText: 'Feedback Mode Off', // optional, default: 'Enter feedback mode'
            exitFeedbackModeText: 'Feedback Mode On',   // optional, default: 'Exit feedback mode'
        });
    </script>
    <?php
}
add_action('wp_head', 'durchblick_widget_enqueue_script');
