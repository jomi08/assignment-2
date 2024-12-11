$(document).ready(function() {
    // Add custom validation styles on input
    $('input, select, textarea').on('input', function() {
        $(this).siblings('.error').fadeOut(300, function() {
            $(this).remove();
        });
    });

    $('#registrationForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#result').html(response.message); // Display the success message
                } else {
                    $('#result').html('<div>Error processing your request.</div>');
                }
            },
            error: function() {
                $('#result').html('<div>Error processing your request.</div>');
            }
        });
    });
    
    function showError(message) {
        $('#result')
            .html(message)
            .removeClass()
            .addClass('result-container error')
            .fadeIn();
    }
});
