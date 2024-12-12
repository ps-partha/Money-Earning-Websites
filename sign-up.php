<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShortURL Signup</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="main-container">
        <form action="" class="Signup Status_default" id="Signup">
            <div class="logo">
                <span><i class="fa fa-link" aria-hidden="true"></i></span>
            </div>
            <h2>Sign up for ShortURL Account</h2>
            <p id="msg" class="error" style="display:none;"></p> <!-- Error message display -->
            <input type="text" name="username" id="username" placeholder="Username" autocomplete="username" required>
            <input type="email" name="email" id="email" placeholder="Email" autocomplete="email" required>
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password"
                required>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password"
                required>
            <button type="submit">Signup</button>
            <div class="other-button">
                <p>or</p>
                <a href="log-in">I already have an account</a>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Cache selectors
            var $msg = $('#msg');
            var $form = $('#Signup');

            // Handle form submission
            $form.on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                // Get form values
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var confirmPassword = $('#confirm-password').val();

                // Validate fields
                if (!username || !email || !password || !confirmPassword) {
                    showMessage("Please fill in all fields", 'red');
                    return;
                }

                if (password !== confirmPassword) {
                    showMessage("Passwords do not match.", 'red');
                    $form.removeClass('Status_default').addClass('Status_error');
                    return;

                }

                // Simple email validation
                var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailPattern.test(email)) {
                    $form.removeClass('Status_default').addClass('Status_error');
                    showMessage("Please enter a valid email.", 'red');
                    return;
                }
                var urlParams = new URLSearchParams(window.location.search);
                var referralValue = urlParams.get('referral') ?? null;
                // Send data to the server (AJAX call)
                $.ajax({
                    url: '/php/signup.php', // URL of your backend script
                    type: 'POST',
                    data: {
                        username: username,
                        email: email,
                        password: password,
                        referral:referralValue
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showMessage(response.message, 'rgb(127, 212, 110)');
                            $form.removeClass('Status_error').removeClass('Status_default').addClass('Status_success');
                            setTimeout(function () {
                                window.location.href = 'log-in.php';
                            }, 1000);
                            $form[0].reset(); // Reset form after successful signup
                        } else {
                            showMessage(response.message || "Signup failed. Please try again.", 'red');
                            $form.removeClass('Status_default').addClass('Status_error');
                            $form[0].reset();
                        }
                    },
                    error: function (xhr) {
                        // Log the full error response
                        console.error("AJAX error:", xhr);
                        // Attempt to get a message from the server response
                        var errorMessage = "There was an error with the signup process. Please try again.";
                        try {
                            // Check if there's a responseText that can be parsed
                            if (xhr.responseText) {
                                var response = JSON.parse(xhr.responseText);
                                errorMessage = response.message || errorMessage; // Fallback to a default message
                            }
                        } catch (e) {
                            console.error("Failed to parse error response:", e);
                        }
                        showMessage(errorMessage, 'red');
                    }
                });

            });

            // Function to display messages
            function showMessage(message, color) {
                $msg.css('color', color).html(message).show();
            }
            $('#Signup input').on('change click', function () {
                $('#msg').hide();
                $('#Signup').addClass('Status_default');
            });
        });
    </script>
</body>

</html>