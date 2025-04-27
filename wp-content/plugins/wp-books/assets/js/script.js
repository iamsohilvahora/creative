jQuery(document).ready(function($) {
    function loadBooks(page, limit, subtitle) {
        $.ajax({
            url: wpBookAjax.ajaxurl,
            type: 'POST',
            data: { action: 'load_books', page: page, limit: limit, showsubtitle: subtitle },
            success: function(response) {
                $('#wp-book-list').html(response.books);
                $('#wp-book-pagination').html(response.pagination);
            }
        });
    }

    // Pagination Click Event
    $(document).on('click', '#wp-book-pagination a', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        // $("#wp-book-pagination a").removeClass('active');
        if (!$(this).hasClass('active')) { // Prevent reloading active page
            loadBooks(page, wpBookAjax.limit, wpBookAjax.subtitle);
        }
    });

    $(document).on('click', '.wp-book-item a', function(e) {
        e.preventDefault();
        var bookID = $(this).data('id');
        $.ajax({
            url: wpBookAjax.ajaxurl,
            type: 'POST',
            data: { action: 'load_book_details', id: bookID, showsubtitle:wpBookAjax.subtitle },
            success: function(response) {
                $('#wp-book-popup').fadeIn();
                $('#wp-book-popup-body').html(response.data);
            }
        });
    });

    $('.wp-book-close').click(function() {
        $('#wp-book-popup').fadeOut();
        $('#wp-book-popup-body').html("");
    });

    // Close Popup when clicking outside the popup content
    $(document).on('click', '#wp-book-popup', function(event) {
        if (!$(event.target).closest('#wp-book-popup-body').length) {
            $('#wp-book-popup').fadeOut();
        }
    });

    // Close Popup when clicking outside the popup content
    $(document).on('click', function(event) {
        if ($('#wp-book-popup').is(':visible') && !$(event.target).closest('.wp-book-popup-content').length) {
            $('#wp-book-popup').fadeOut();
        }
    });
});
