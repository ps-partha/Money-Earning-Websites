<?php
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['user_id'])) {
    header('Location: ../log-in');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/design.main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="main-container flex">
        <div class="left-content" id="leftContent">
            <a href="../home">
                <div class="logo">
                    <h2>ShortURL</h2>
                    <span class="wclose-nav"><i class="fa fa-spinner" aria-hidden="true"></i></span>
                </div>
            </a>
            <span class="nav-close" id='nav_close'><i id="menubr" class="fa fa-times" aria-hidden="true"></i></span>
            <div class="side-nav">
                <ul>
                    <li id="dashboardBtn">
                        <i class="fa fa-caret-square-o-right" aria-hidden="true"></i> Dashboard
                    </li>
                    <li id="AddLinkBtn">
                        <i class="fa fa-plus" aria-hidden="true"></i> Create Link
                    </li>
                    <li id="AllLinkBtn">
                        <i class="fa fa-link" aria-hidden="true"></i> All Links
                    </li>
                    <li id="Paymentsnav">
                        <i class="fa fa-credit-card" aria-hidden="true"></i> Payments
                    </li>

                    <li id="Referralsnav">
                        <i class="fa fa-users" aria-hidden="true"></i> Referrals
                    </li>

                    <!-- <li>
                        <i class="fa fa-diamond" aria-hidden="true"></i> Upgrade
                    </li> -->

                </ul>
            </div>
        </div>

        <div class="right-content">
            <div class="Header-part">
                <div class="head-part-left">
                    <span class="menu" id="menu"><i id="menubr" class="fa fa-bars" aria-hidden="true"></i></span>
                </div>
                <div class="nd-logo">
                    <h2>ShortURL</h2>
                    <span class="wclose-nav"><i class="fa fa-spinner" aria-hidden="true"></i></span>
                </div>
                <div class="head-part-right">
                    <div class="user">
                        <span id="profile-h"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                    </div>
                    <div class="dropdown_m">
                        <ul>
                            <div class="dropdown-up">
                                <div class="arrow-up"></div>
                                <small>Welcome!</small>
                                <li class='profile_btn'><i class="fa fa-user" aria-hidden="true"></i> My profile</li>
                                <li id='BalanceE'></li>

                            </div>
                            <li class='logout-session'>
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Sign-out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Dashboard Section -->
            <div class="main-content" id="Dashboard" style="display: none;">
                <div class="welcome-users">
                    <h2>Welcome Back,
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </h2>
                    <p>Earn 5% of your referrals’ revenue by recommending us</p>
                </div>
                <div class="items-list">
                    <div class="row">
                        <div class="item">

                            <div class="item-text">
                                <small>Total Views</small>
                                <p id='TotalView'>0</p>
                                <div class="card-footer">
                                    <small class="percentage"><i class="fa fa-arrow-up"
                                            aria-hidden="true"></i>100%</small>
                                    <small>|</small>
                                    <small class="date"></small>
                                </div>
                            </div>
                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>
                        </div>
                        <div class="item">
                            <div class="item-text">
                                <small>Total Earnings</small>
                                <p id='TotalEarning'>$0.00</p>

                                <div class="card-footer">
                                    <small class="percentage"><i class="fa fa-arrow-up"
                                            aria-hidden="true"></i>100%</small>
                                    <small>|</small>
                                    <small class="date"></small>
                                </div>
                            </div>
                            <span><i class="fa fa-money" aria-hidden="true"></i></span>

                        </div>
                        <div class="item">
                            <div class="item-text">
                                <small>Total REF Earn</small>
                                <p id='TotalREF'>0</p>

                                <div class="card-footer">
                                    <small class="percentage"><i class="fa fa-arrow-up"
                                            aria-hidden="true"></i>100%</small>
                                    <small>|</small>
                                    <small class="date"></small>
                                </div>
                            </div>
                            <span><i class="fa fa-users" aria-hidden="true"></i></span>
                        </div>
                        <div class="item">

                            <div class="item-text">
                                <small>AVG CPM</small>
                                <p>$2.50</p>

                                <div class="card-footer">
                                    <small class="percentage"><i class="fa fa-arrow-up"
                                            aria-hidden="true"></i>100%</small>
                                    <small>|</small>
                                    <small class="date">27-September</small>
                                </div>
                            </div>
                            <span><i class="fa fa-line-chart" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>
                <div class="report-canvas">
                    <div class="report-head">
                        <p>Statistics</p>
                        <p><select name="" id="">
                                <option value="September-2024" id></option>
                            </select></p>
                    </div>
                    <canvas id="myChart"></canvas>
                </div>



            </div>

            <!-- Add Link Section -->
            <div class="Add-Link" id="AddLink">
            <div class="add-link-content">
            <h2>Paste the URL to be shortened</h2>
            <form id="urlForm">
                <div class="input-group">
                    <input class="shortened" type="text" placeholder="Enter link here" name="url" id="URL" aria-label="Example input" required />
                    
                    <div class='add-link-footer'>
                        <button type="submit" id="button">Shorten URL</button>

                        <div class="add-link-show-copy hide_some">  
                            <!-- Input for displaying the shortened URL -->
                            <input type="text" id="S__Url" readonly>
                            
                            <!-- Copy button -->
                            <span class='copy-btn' id="_COpyBTn">
                                <i class="fa fa-clone" aria-hidden="true"></i>
                            </span>
                            
                            <!-- Tooltip for copied text -->
                            <div class="copy_topic">copy
                                <div class="arrow_up"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

            </div>
            <!-- show AllLink Section -->
            <div class="all-link" id="AllLink">
                <h3>All Recent URLs</h3>

            </div>

            <!-- The Modal -->
            <div id="editModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>Edit URL</h2>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="editUrlInput" placeholder="Enter new URL">
                    </div>
                    <div class="modal-footer">
                        <button class="btn3 btn" id="closeModalBtn">Close</button>
                        <button class="btn4 btn" id="saveEdit">Save changes</button>
                    </div>
                </div>
            </div>
            <!-- show Payments Section -->
            <div class="Payments" id="Payments" style="display: none;">
                            <div class="message" id="message">
                    <p id="msg">fdf</p>
                </div>
                <div class="PaymentsAmountInfo">
                    <p>Minimum withdrawal is $10.00</p>
                    <p>If your payment day is set to a weekend please expect the payment at the beginning of the
                        following week. In case you have any questions, feel free to write to us at contact us</p>
                </div>
                <div class="PaymentsStatus" style='display:block'>
                    <div class="info-left">
                        <div class="items-list">
                            <div class="row">
                                <div class="item">
                                    <div class="item-text">
                                        <small>Available Balance</small>
                                        <p id='Total_Earning'>0</p>
                                        <div class="card-footer">
                                            <small class="date"></small>
                                        </div>
                                    </div>
                                    <span class="wf"><i class="fa fa-money" aria-hidden="true"></i></span>
                                </div>
                                <div class="item">
                                    <div class="item-text">
                                        <small>Pending Withdrawn</small>
                                        <p id='totalPending'>$0.00</p>

                                        <div class="card-footer">
                                            <small class="date"></small>
                                        </div>
                                    </div>
                                    <span class="wf"><i class="fa fa-clock-o" aria-hidden="true"></i></span>

                                </div>
                                <div class="item">
                                    <div class="item-text">
                                        <small>Total Withdraw</small>
                                        <p id='totalApproved'>0</p>

                                        <div class="card-footer">
                                            <small class="date"></small>
                                        </div>
                                    </div>
                                    <span class="wf"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-right">
                    <div class="Withdraw_btn">
                        <button id='withdrawButton'>Withdraw</button>
                    </div>
                    <p>Following account creation, you can request your earnings. If your balance reaches the required
                        amount or beyond, click the button above. The money is transferred to your withdrawal account
                        within a business day and no later than one week. Please do not contact us about payments before
                        the deadlines. If you still need to, fill out your payment method and ID here to receive your
                        payments. </p>
                    <h3>Payments history</h3>
                    <table id='PaymentsList'>
                        <tr class="table-head">
                            <th>Id</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>


                    </table>

                </div>
            </div>
            <!-- show Referrals Section -->
            <div class="Referrals" id="Referrals">
                <h2>Referral Program</h2>
                <p>Earn 5% of your referrals’ revenue by recommending us</p>
                <div class="ReferralProgramstep">
                    <div class="ReferralStep">
                        <h4>Share the link with your referral</h4>
                        <p>Once your publisher account is approved, you will get a referral link for sharing.</p>
                    </div>
                    <div class="ReferralStep">
                        <h4>Your referral join</h4>
                        <p>A new publisher you have brought should sign up using the referral link.</p>
                    </div>
                    <div class="ReferralStep">
                        <h4>Get rewarded</h4>
                        <p>You will earn 5% of all the referral’s revenue.</p>
                    </div>
                </div>
                <h3>Share your link</h3>
                <p>Add it to your website or landing page, or send it personally via email.</p>

                <div class="Referralslink">

                </div>

                <div class='Referrals-list'>
                    <table id="ReferralsList">
                        <tr>
                            <th>Id</th>
                            <th>username </th>
                            <th>balance</th>
                            <th>Country</th>
                        </tr>


                    </table>

                </div>
            </div>
            <div class="profile" id='profile' style="display: none;">
                <div class="left-header">
                    <div class="Billing_Address">
                        <form id="save_billing_info">
                            <h4>Billing Address</h4>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="First_Name">First Name</label>
                                    <input type="text" class="form-control" id="First_Name" name="First_Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="Last_Name">Last Name</label>
                                    <input type="text" class="form-control" id="Last_Name" name="Last_Name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="Address">Address</label>
                                    <input type="text" class="form-control" id="Address" name="Address">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="City">City</label>
                                    <input type="text" class="form-control" id="City" name="City">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="State">State</label>
                                    <input type="text" class="form-control" id="State" name="State">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="Country">Country</label>
                                    <input type="text" class="form-control" id="Country" name="Country">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ZIP">ZIP</label>
                                    <input type="text" class="form-control" id="ZIP" maxlength="6" name="ZIP">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="Phone_Number">Phone Number</label>
                                    <input type="text" class="form-control" id="Phone_Number" name="Phone_Number">
                                </div>
                            </div>

                            <div class="Billing_Address">
                                <h4>Withdrawal Info</h4>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="Withdrawal_Method">Withdrawal Method</label>
                                        <select name="Withdrawal_Method" class="form-control" id="Withdrawal_Method">
                                            <option value="paypal">Paypal</option>
                                            <option value="binance">Binance Pay</option>
                                            <option value="bkash">bKash</option>
                                            <option value="payoneer">Payoneer</option>
                                        </select>
                                        <label for="Withdrawal_Account">Withdrawal Account</label>
                                        <input type="text" class="form-control" id="Withdrawal_Account"
                                            name="Withdrawal_Account">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <table>
                                            <tr>
                                                <th>Withdraw Method</th>
                                                <th>Minimum Withdrawal Amount</th>
                                            </tr>
                                            <tr>
                                                <td>PayPal</td>
                                                <td>$10.00</td>
                                            </tr>
                                            <tr>
                                                <td>Binance Pay</td>
                                                <td>$20.00</td>
                                            </tr>
                                            <tr>
                                                <td>bKash (Bangladesh) Account</td>
                                                <td>$5.00</td>
                                            </tr>
                                            <tr>
                                                <td>Payoneer</td>
                                                <td>$10.00</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function inactivesection() {
                $('#dashboardBtn').removeClass('active');
                $('#AddLinkBtn').removeClass('active');
                $('#AllLinkBtn').removeClass('active');
                $('#Paymentsnav').removeClass('active');
                $('#Referralsnav').removeClass('active');
                $('#Dashboard').hide();
                $('#AllLink').hide();
                $('#AddLink').hide();
                $('#Payments').hide();
                $('#Referrals').hide();
                $('#profile').hide();
            }
            // Load last active section (same as your previous code)
            function showSection(section) {
                $('#Dashboard, #AddLink, #AllLink').hide();
                $('.btn').removeClass('active');
                if (section === 'AddLink') {
                    inactivesection();
                    $('#AddLinkBtn').addClass('active');
                    $('#AddLink').show();


                } else if (section === 'AllLink') {
                    inactivesection();
                    $('#AllLinkBtn').addClass('active');
                    $('#AllLink').show();


                } else if (section === 'Payments') {
                    inactivesection();
                    $('#Paymentsnav').addClass('active');
                    $('#Payments').show();

                }
                else if (section === 'Referrals') {
                    inactivesection();
                    $('#Referralsnav').addClass('active');
                    $('#Referrals').show();
                }
                else if (section === 'Profile') {
                    inactivesection();
                    $('#profile').show();
                }
                else {
                    inactivesection();
                    $('#dashboardBtn').addClass('active');
                    $('#Dashboard').show();

                }
            }

            function loadSection() {
                let activeSection = window.location.hash.substring(1) || localStorage.getItem('activeSection') || 'Dashboard';
                showSection(activeSection);
                localStorage.setItem('activeSection', activeSection);
            }

            window.addEventListener('hashchange', loadSection);
            loadSection();

            $('#dashboardBtn').click(function () {
                window.location.hash = 'Dashboard';

            });
            $('#AddLinkBtn').click(function () {
                window.location.hash = 'AddLink';
            });
            $('#AllLinkBtn').click(function () {
                window.location.hash = 'AllLink';
            });
            $('#Paymentsnav').click(function () {
                window.location.hash = 'Payments';
            });
            $('#Referralsnav').click(function () {
                window.location.hash = 'Referrals';
            });
            $('.profile_btn').click(function () {
                window.location.hash = 'Profile';
                $('.dropdown_m').toggleClass('show');
            });

            // Toggle side menu
            $('#menu').click(function () {
                $('#leftContent').toggleClass('show');
                // $('#menubr').toggleClass('fa-bars ');
            });
            $('#nav_close').click(function () {
                $('#leftContent').toggleClass('show');
                // $('#menubr').toggleClass('fa-bars ');
            });


            $('#profile-h').click(function () {
                $('.dropdown_m').toggleClass('show');
            });

            function isValidUrl(url) {
                try {
                    new URL(url);
                    return true;
                } catch (e) {
                    return false;
                }
            }

            function fetchUrls() {
                $.ajax({
                    url: '/php/get_dashboard_info.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $('#totalApproved').html(`$${response.totalApproved}`);
                        $('#totalPending').html(`$${response.totalPending}`);
                        
                        if (response.status === 'success' && Array.isArray(response.urls)) {
                            response.urls.forEach(function (urlData) {
                                const url = new URL(urlData.original_url);
                                const AllUrl = `
                                <div class="link-card" id="Show_Link-${urlData.short_code}">
                                    <p id="ShortURL">${url.hostname}</p>
                                    <div class="small_text"><i class="fa fa-calendar" aria-hidden="true"></i> <small>${urlData.created_at} - ${urlData.original_url}</small></div>
                                    <div class="card-right-footer">
                                        <div class="show-copy">
                                            <input type="text" value="https://url.skipthegames.tech/${urlData.short_code}">
                                            <span class='copy-btn' data-url="https://url.skipthegames.tech/${urlData.short_code}">
                                                <i class="fa fa-clone" aria-hidden="true"></i>
                                            </span>
                                            <div class="topic" id='topic-${urlData.short_code}' data-topic="${urlData.short_code}">copy
                                                <div class="arrow_up"></div>
                                            </div>
                                        </div>
                                        <div class="card-right-buttons">
                                            <span class="btn1 btn" id="openModalBtn" role="button" tabindex="0" data-shortcode="${urlData.short_code}" data-o_url="${urlData.original_url}">
                                                <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                            </span>
                                            <span class="btn2 btn" role="button" tabindex="0" data-o_url="${urlData.original_url}" data-shortcode="${urlData.short_code}">
                                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                                $('#AllLink').append(AllUrl);
                            });

                            // Attach copy functionality to all copy buttons after appending the URLs
                            $('.copy-btn').off('click').on('click', function () {
                                const id = $(this).siblings('.topic').data('topic'); // Get the topic id associated with the copy button
                                const shortenedUrl = $(this).data('url'); // Get the URL to copy

                                navigator.clipboard.writeText(shortenedUrl)
                                    .then(() => {
                                        // Toggle the topic tooltip visibility when copying is successful
                                        $(`#topic-${id}`).html('Copied!');
                                    })
                                    .catch(err => alert('Failed to copy link: ', err));
                            });
                            // Keyboard support for copy buttons
                            $('.copy-btn').off('keydown').on('keydown', function (e) {
                                if (e.key === "Enter" || e.key === " ") {
                                    $(this).click();
                                }
                            });
                            // Attach edit functionality to all Edit buttons after appending the URLs
                            $('.btn1').off('click').on('click', function () {
                                const shortCode = $(this).data('shortcode');
                                const originalUrl = $(this).data('o_url');
                                $('#editModal').show();
                                $('#editUrlInput').val(originalUrl);
                                $('#saveEdit').off('click').on('click', function () {
                                    const updatedUrl = $('#editUrlInput').val();
                                    // Make an AJAX request to update the URL
                                    $.ajax({
                                        url: '/php/update-url.php', // Update this with your API endpoint
                                        type: 'POST',
                                        data: { short_code: shortCode, original_url: updatedUrl, status: 'update' },
                                        success: function (response) {
                                            if (response.status === 'success') {
                                                // Update the original URL on the card
                                                $(`#OrginalUrl-${shortCode}`).text(updatedUrl);
                                                // Close the modal
                                                $('#editModal').hide();
                                            }
                                        },
                                        error: function () {
                                            alert('Failed to update the URL');
                                        }
                                    });
                                });
                            });
                            $('.close').off('click').on('click', function () {
                                $('#editModal').hide();
                            });
                            $(window).off('click').on('click', function (event) {
                                if ($(event.target).is('#editModal')) {
                                    $('#editModal').hide();
                                }
                            });
                            $('#closeModalBtn').off('click').on('click', function () {
                                $('#editModal').hide();
                            });

                            $('.btn2').off('click').on('click', function () {
                                const shortCode = $(this).data('shortcode');

                                // Confirm before deletion
                                const confirmDelete = confirm("Are you sure you want to delete this URL?");

                                if (confirmDelete) {
                                    $.ajax({
                                        url: '/php/update-url.php', // Update this with your API endpoint
                                        type: 'POST',
                                        data: { short_code: shortCode, status: 'delete' },
                                        success: function (response) {
                                            if (response.status === 'success') {
                                                // Remove the URL card from the DOM after deletion
                                                $(`#Show_Link-${shortCode}`).remove();
                                            } else {
                                                alert('Failed to delete the URL');
                                            }
                                        },
                                        error: function () {
                                            alert('Failed to delete the URL');
                                        }
                                    });
                                }
                            });

                        } else {
                            alert(response.message || "Unexpected response format");
                        }
                        // Handling referred users
                        if (response.status === 'success' && Array.isArray(response.referred_users)) {
                            const XXX = `
                            <div class="show-copy" style='width:70%'>
                                <input type="text" id="C_Url-${response.referral_code}" value="https://url.skipthegames.tech/sign-up?referral=${response.referral_code}" >
                                <span class='copy-btn' id="ReferralB" data-url="https://url.skipthegames.tech/sign-up?referral=${response.referral_code}">
                                    <i class="fa fa-clone" aria-hidden="true"></i>
                                </span>
                                <div class="topic" id='XASd' data-topic="${response.referral_code}">
                                    copy
                                    <div class="arrow_up"></div>
                                </div>
                            </div> `;

                            // Append the referral link to the container
                            $('.Referralslink').append(XXX);
                            // Attach click event handler to copy the referral link
                            $('#ReferralB').off('click').on('click', function () {
                                // Get the referral code from the data attribute
                                const referralCode = $('#XASd').data('topic');
                                const referralUrl = $(`#C_Url-${referralCode}`).val();
                                navigator.clipboard.writeText(referralUrl)
                                    .then(() => {
                                        $(`#XASd`).html('Copied!');
                                    })
                                    .catch(err => {
                                        alert('Failed to copy link: ' + err);
                                    });
                            });
                            response.referred_users.forEach(function (referred, index) {
                                const AllReferrenc = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${referred.username}</td>
                                    <td>${referred.balance}</td>
                                    <td>${referred.country}</td>
                                </tr>`;
                                $('#ReferralsList').append(AllReferrenc);
                            });
                        } else {
                            alert(response.message || "Unexpected response format");
                        }
                        // Handling payments
                        if (response.status === 'success' && Array.isArray(response.payments)) {
                        response.payments.forEach(function (payment, index) {
                            // Convert the amount based on the currency
                            let finalAmount = (payment.currency === 'bkash') ? payment.amount * 85 : payment.amount;
                            const statusColor = (payment.payment_status === 'Pending') ? 'pending-color' : 'approved-color'; // Set class 
                            // Generate the table row HTML
                            const AllPayments = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${payment.payment_method}</td>
                                <td>${finalAmount} ${payment.currency}</td>
                                <td class="${statusColor}">${payment.payment_status}</td>
                                <td>${payment.created_at}</td>
                            </tr>`;
                            
                            // Append the generated row to the table body with ID "PaymentsList"
                            $('#PaymentsList').append(AllPayments);
                        });
                    } else {
                        // Handle unexpected or error response
                        alert(response.message || "Unexpected response format");
                    }

                    },
                    error: function (xhr) {
                        alert('Error fetching URLs: ' + xhr.responseText);
                    }
                });
            }

            fetchUrls();
            // Handle form submission for shortening URL
            // URL validation function

            $('#urlForm').on('submit', function (e) {
                e.preventDefault();
                var longUrl = $('#URL').val();

                if (isValidUrl(longUrl)) {
                    $('#button').prop('disabled', true); // Disable button during AJAX request
                    
                    $.ajax({
                        url: '../shorten.php',
                        type: 'POST',
                        data: { longUrl: longUrl },
                        dataType: 'json',
                        success: function (response) {
                            $('#button').prop('disabled', false); // Re-enable button

                            if (response.status === 'success') {
                                $('.add-link-show-copy').toggleClass('show_some'); // Assuming you want to show the copy section
                                
                                // Set shortened URL in the input field
                                $('#S__Url').val(response.shortenedUrl);

                                // Attach the click event for copying the URL
                                $('#_COpyBTn').off('click').on('click', function () {
                                    navigator.clipboard.writeText($('#S__Url').val()) // Copy URL from input
                                        .then(() => {
                                            // Show 'Copied!' message
                                            $('.copy_topic').html('Copied!');
                                            $('.add-link-show-copy').toggleClass('show_some');
                                            $('#URL').val('');
                                        })
                                        .catch(err => alert('Failed to copy link: ' + err));
                                });
                            } else {
                                alert('Failed to shorten URL.');
                            }
                        },
                        error: function () {
                            $('#button').prop('disabled', false); // Re-enable button
                            alert('An error occurred while trying to shorten the URL.');
                        }
                    });
                } else {
                    $('#URL').val('Invalid URL. Please try again.');
                }
            });


            // URL input field validation
            $('#URL').on('input', function () {
                const url = $(this).val();
                $(this).css("border", isValidUrl(url) ? "1px solid green" : "1px solid #be3144");
            });

            // Button click event for URL validation
            $('#button').on('click', function () {
                const urlInput = $('#URL');
                urlInput.focus();
                urlInput.css("border", isValidUrl(urlInput.val()) ? "1px solid green" : "1px solid #be3144");
            });


            // Logout session handling
            $(document).on('click', '.logout-session', function () {
                if (confirm('Are you sure you want to log out this session?') == true) {
                    $.ajax({
                        url: '/php/logout_session.php',
                        type: 'GET',
                        success: function (response) {
                            console.log(response);
                            if (response.status === 'success') {
                                window.location.href = '../';
                            }

                        },
                        error: function (xhr) {
                            console.error("Error logging out session: ", xhr.responseText);
                        }
                    });
                }
            });
        });

        
        // save_billing_info 
        $('#save_billing_info').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '/php/save_billing_info.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                    alert(response.message); // Notify the user to fill in profile info
                } else {
                    // Billing info is missing
                    alert(response.message); // Notify the user to fill in profile info
                }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error: ' + status + error);
                }
            });
        });
        // withdrow request
    $('#withdrawButton').on('click', function (e) {
        e.preventDefault(); // Prevent default action

        // First, check if the billing info is complete
        $.ajax({
            url: '/php/check_billing_info.php', // URL of the PHP script to check billing info
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                  if (response.status === 'success') {
                         alert(response.message);
                    } else {
                        alert(response.message);
                        
                    }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
                alert('An error occurred while checking billing information.');
            }
        });
    });

        // Date display and chart generation
        $(document).ready(function () {
            const date = new Date();
            const day = date.getDate();
            const options = { month: 'long' };
            const month = date.toLocaleDateString('en-US', options);
            const formattedDate = `${day}-${month}`;
            $('.date').html(formattedDate);

            // Fetch data from the PHP endpoint for chart
            fetch('/php/endpoint.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    const yValues = [
                        data.view_counts_3,data.view_counts_6, data.view_counts_9, data.view_counts_12,
                        data.view_counts_15, data.view_counts_18, data.view_counts_21,
                        data.view_counts_24, data.view_counts_27, data.view_counts_30
                    ];

                    const maxValue = Math.max(...yValues);
                    $('#TotalView').html(data.total_view_count);
                    $('#Totalbalance').html(`Current Balance is $${data.total_balance}`);
                    $('#TotalREF').html(data.total_referrals);
                    $('#TotalEarning').html(`$${data.total_balance}`);
                    $('#Total_Earning').html(`$${data.total_balance}`);
                    $('#BalanceE').html(`<i class="fa fa-usd" aria-hidden="true"></i> Balance : $${data.total_balance}`);

                    localStorage.setItem('Balance', data.total_balance);
                    const minValue = 0;

                    // Create the chart
                    new Chart("myChart", {
                        type: "line",
                        data: {
                            labels: [3,6, 9, 12, 15, 18, 21, 24, 27, 30], // X-values
                            datasets: [{
                                fill: false,
                                backgroundColor: "#02AAB0",
                                borderColor: "#02AAB0",
                                data: yValues
                            }]
                        },
                        options: {
                            legend: { display: false },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: minValue,
                                        max: maxValue,
                                        stepSize: (maxValue / 10) // Y-axis ticks
                                    }
                                }]
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        });

    </script>
</body>

</html>