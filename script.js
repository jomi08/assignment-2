$(document).ready(function() {
    // Add custom validation styles on input
    $('input, select, textarea').on('input', function() {
        $(this).siblings('.error').fadeOut(300, function() {
            $(this).remove();
        });
    });

    $('#registrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Remove any existing error messages
        $('.error').remove();
        
        let isValid = true;
        const form = $(this);
        const submitBtn = form.find('.submit-btn');
        
        // Validate full name
        const fullName = $('#fullName').val().trim();
        if (fullName.length < 2) {
            $('#fullName').after('<span class="error">Please enter a valid name (minimum 2 characters)</span>');
            isValid = false;
        }
        
        // Validate email
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $('#email').after('<span class="error">Please enter a valid email address</span>');
            isValid = false;
        }
        
        // Validate phone (accept different formats)
        const phone = $('#phone').val().trim().replace(/[- )(]/g, '');
        const phoneRegex = /^\+?\d{10,14}$/;
        if (!phoneRegex.test(phone)) {
            $('#phone').after('<span class="error">Please enter a valid phone number (10-14 digits)</span>');
            isValid = false;
        }
        
        // Validate date of birth
        const dob = $('#dob').val();
        if (!dob) {
            $('#dob').after('<span class="error">Please select your date of birth</span>');
            isValid = false;
        } else {
            // Check if the person is at least 18 years old
            const dobDate = new Date(dob);
            const today = new Date();
            const age = today.getFullYear() - dobDate.getFullYear();
            if (age < 18) {
                $('#dob').after('<span class="error">You must be at least 18 years old</span>');
                isValid = false;
            }
        }
        
        // Validate gender
        const gender = $('#gender').val();
        if (!gender) {
            $('#gender').after('<span class="error">Please select your gender</span>');
            isValid = false;
        }
        
        // Validate address
        const address = $('#address').val().trim();
        if (address.length < 10) {
            $('#address').after('<span class="error">Please enter a complete address (minimum 10 characters)</span>');
            isValid = false;
        }
        
        if (isValid) {
            // Add loading state
            submitBtn.addClass('loading').prop('disabled', true);
            
            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: 'process.php',
                data: form.serialize(),
                success: function(response) {
                    try {
                        const result = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        // Smooth scroll to result
                        const resultDiv = $('#result');
                        resultDiv
                            .html(result.message)
                            .removeClass()
                            .addClass('result-container ' + (result.success ? 'success' : 'error'))
                            .fadeIn();
                        
                        $('html, body').animate({
                            scrollTop: resultDiv.offset().top - 20
                        }, 500);
                        
                        if (result.success) {
                            form[0].reset();
                        }
                    } catch (e) {
                        showError('Invalid response from server');
                    }
                },
                error: function(xhr, status, error) {
                    showError('An error occurred while submitting the form. Please try again.');
                },
                complete: function() {
                    // Remove loading state
                    submitBtn.removeClass('loading').prop('disabled', false);
                }
            });
        } else {
            // Smooth scroll to first error
            const firstError = $('.error').first();
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    });
    
    function showError(message) {
        $('#result')
            .html(message)
            .removeClass()
            .addClass('result-container error')
            .fadeIn();
    }
});
