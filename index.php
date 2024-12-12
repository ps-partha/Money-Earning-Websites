
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>url shortener - Home</title>
    <link rel="stylesheet" href="./css/design.main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X8SHHL15BQ"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-X8SHHL15BQ');
    </script>

</head>

<body class="Home-body">
    <main>
        <div class="Header-section">
            <div class="header-nav" id="nav_bar"></div>
            <header>
                <div class="header-left">
                    <h2>Share Your Link & Earn Money</h2>
                    <p>Your Can share links to make money! We offer the highest CPMs without any limits! </p>
                    <p><a href="https://url.skipthegames.tech/member/dashboard#AllLink"><i class="fa fa-arrow-right"
                                aria-hidden="true"></i> Start Shareing</a></p>
                </div>
                <div class="header-right">
                    <img src="images/header-pic.jpg" alt="">
                </div>
            </header>
        </div>
        <h2 class="title">Earn money in just 3 steps!</h2>
        <div class="working-steps">
            <div class="step step1">
                <div class="head-icons">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                </div>
                <div class="step-title">
                    <span>1</span>
                    <h2> Create an account</h2>
                </div>
                <p>The sign up process is very easy and should not take more than 2 minutes</p>
            </div>
            <div class="step step1">
                <div class="head-icons">
                    <i class="fa fa-share" aria-hidden="true"></i>
                </div>
                <div class="step-title">
                    <span>2</span>
                    <h2> Share Links</h2>
                </div>
                <p>After signing up. You can use our platform to share links of any type of website.</p>
            </div>
            <div class="step step1">
                <div class="head-icons">
                    <i class="fa fa-money" aria-hidden="true"></i>

                </div>
                <div class="step-title">
                    <span>3</span>
                    <h2>Earn money</h2>
                </div>
                <p>Share your links with your friends and followers and get paid for every visitor! </p>
            </div>

        </div>
        <h2 class="title">Why Choose Us?</h2>
        <div class="choosing-steps">
            <div class="choosing-step">
                <h3>Easy steps to start earning!</h3>
                <p>There is nothing you should do after creating an account to start earnings. The website has a very
                    simple dashboard and you can start sharing your file immediately.</p>
            </div>
            <div class="choosing-step">
                <h3>Easy steps to start earning!</h3>
                <p>There is nothing you should do after creating an account to start earnings. The website has a very
                    simple dashboard and you can start sharing your file immediately.</p>
            </div>
            <div class="choosing-step">
                <h3>Easy steps to start earning!</h3>
                <p>There is nothing you should do after creating an account to start earnings. The website has a very
                    simple dashboard and you can start sharing your file immediately.</p>
            </div>
        </div>
    </main>
    <footer class="footer-nav" id="Footer"></footer>
</body>
<script>
    $(document).ready(function () {
        $('#Footer').load('./components/footer.html');
        $('#nav_bar').load('./components/nav.html');
    });
    
    
</script>
</html>
