<?php
/*
Plugin Name: AI Chatbot Plugin
Description: Integration of AI Chatbot with RAG and Chain of Thought
Version: 1.0
Author: Your Name
*/

function enqueue_chatbot_scripts() {
    wp_enqueue_script('chatbot-script', plugins_url('/js/chatbot.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('chatbot-script', 'chatbotAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_chatbot_scripts');

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
?>
