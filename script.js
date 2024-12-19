function submitForm(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById('registrationForm');
    const resultContainer = document.getElementById('result');

    // Use FormData to capture form values
    const formData = new FormData(form);

    // Make the form submission using Fetch API
    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            Accept: 'application/json',
        },
    })
        .then((response) => {
            if (response.ok) {
                // Clear the form
                form.reset();

                // Display a success message
                resultContainer.innerHTML = `
                    <div class="success-message">
                        Thank you for registering! Your submission has been received.
                    </div>
                `;
                resultContainer.classList.add('success');

                // Remove the message after 4 seconds
                setTimeout(() => {
                    resultContainer.innerHTML = '';
                    resultContainer.classList.remove('success');
                }, 4000);
            } else {
                // Display an error message
                resultContainer.innerHTML = `
                    <div class="error-message">
                        Something went wrong. Please try again later.
                    </div>
                `;
                resultContainer.classList.add('error');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            resultContainer.innerHTML = `
                <div class="error-message">
                    An error occurred. Please check your connection and try again.
                </div>
            `;
            resultContainer.classList.add('error');
        });
}
