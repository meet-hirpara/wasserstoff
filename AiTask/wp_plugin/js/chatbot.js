jQuery(document).ready(function($) {
    $('#chat-submit').click(function(e) {
        e.preventDefault();
        var userQuery = $('#chat-input').val();
        $.ajax({
            url: chatbotAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'chatbot_query',
                query: userQuery
            },
            success: function(response) {
                $('#chat-response').text(response.response);
            }
        });
    });
});
