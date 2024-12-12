<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    header('Location: member/dashboard');
    exit();
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShortURL Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="main-container">
        <form action="" class="Signup Status_default" id="Login">
            <div class="logo">
                <span><i class="fa fa-link" aria-hidden="true"></i></span>
            </div>
            <h2>Log in to ShortURL</h2>
            <p id="msg" class="error" style="display:none;"></p> <!-- Error message display -->
            <input type="text" name="usernameoremail" id="usernameoremail" placeholder="Username or Email" autocomplete="email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <div class="forgot-password">
                <input type="checkbox" name="" id="">
                <a href="">Forgot Password?</a>
            </div>
            <button type="submit">Login</button>
            <div class="other-button">
                <p>or</p>
                <a href="sign-up">I don't have an account</a>
            </div>
        </form>
        
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#Login').on('submit', function (e) {
            e.preventDefault();
            var usernameOrEmail = $('#usernameoremail').val().trim();
            var password = $('#password').val().trim();
            if (usernameOrEmail === "" || password === "") {
                $('#msg').css('color', 'red').html("Please fill in all fields").show();
                return;
            }
            $.ajax({
                url: '/php/login.php', // Path to the PHP login script
                type: 'POST',
                dataType: 'json',
                data: {
                    usernameoremail: usernameOrEmail,
                    password: password
                },
                success: function (response) {
                    $('#msg').show();
                    if (response.status === 'success') {
                        $('#msg').css('color', 'rgb(127, 212, 110)').html(response.message);
                        $('#Login').removeClass('Status_error').removeClass('Status_default').addClass('Status_success');
                        setTimeout(function () {
                            window.location.href = 'member/dashboard';
                        }, 1000);
                    } else {
                        $('#Login').removeClass('Status_default').addClass('Status_error');
                        $('#msg').css('color', 'red').html(response.message);
                        $('#usernameoremail').val(''); // Clear fields on error
                        $('#password').val('');
                    }
                },
                error: function (xhr, status, error) {
                    let errorMsg = "An error occurred. Please try again.";
                    if (xhr.status === 404) {
                        errorMsg = "Login service not found.";
                    } else if (xhr.status === 500) {
                        errorMsg = "Server error. Please contact support.";
                    }
                    $('#msg').css('color', 'red').html(errorMsg).show();
                    console.error("Error details: ", xhr.responseText);
                },
                complete: function () {
                }
            });
        });

        // Reset message and style on input change or click
        $('#Login input').on('input click', function () {
            $('#msg').hide(); // Hide the message on user interaction
            $('#Login').removeClass('Status_error').addClass('Status_default');
        });
    });
</script>
</html>
<?php
}
?>