jQuery(document).ready(function ($) {
    $('#testimonial-search-form').on('keyup', function (e) {
        e.preventDefault();
        const title = $('#testimonial-title').val();
        $.ajax({
            url: ajax_search_params.ajax_url,
            type: 'POST',
            data: {
                action: 'testimonial_ajax_search',
                title: title,
            },
            beforeSend: function () {
                $('#search-results').html('<p>Searching...</p>');
            },
            success: function (response) {
                $('#search-results').html(response.data);
            },
            error: function () {
                $('#search-results').html('<p>An error occurred. Please try again.</p>');
            },
        });
    });
});
