<?php

function enqueue_chatbot_assets() {
    wp_enqueue_script('chatbot-script', plugins_url('/js/chatbot.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('chatbot-style', plugins_url('/css/chatbot.css', __FILE__));
    wp_localize_script('chatbot-script', 'chatbotAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'enqueue_chatbot_assets');

function chatbot_ajax_handler() {
    $query = sanitize_text_field($_POST['query']);
    $response = wp_remote_post('http://localhost:5000/chat', array(
        'method' => 'POST',
        'body' => json_encode(array('query' => $query)),
        'headers' => array('Content-Type' => 'application/json')
    ));
    echo wp_remote_retrieve_body($response);
    wp_die();
}

add_action('wp_ajax_chatbot_query', 'chatbot_ajax_handler');
add_action('wp_ajax_nopriv_chatbot_query', 'chatbot_ajax_handler');

function display_chatbot() {
    ?>
    <div id="chatbot-container">
        <input type="text" id="chat-input" placeholder="Ask me anything...">
        <button id="chat-submit">Send</button>
        <div id="chat-response"></div>
    </div>
    <?php
}

add_shortcode('chatbot', 'display_chatbot');
?>
