<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";

$plan_id = $_GET['plan'] ?? 1;
$plan_name = $_GET['name'] ?? 'Silver';
$price = $_GET['price'] ?? 30;

// Handle Payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_SESSION['member_id'];
    $duration_months = 1;
    if ($plan_name == 'Gold') $duration_months = 6;
    if ($plan_name == 'Platinum') $duration_months = 12;

    // Calculate new end date
    // If already active, add to existing end date. Else start from today.
    $check_sql = "SELECT subscription_end FROM members WHERE id=$member_id";
    $row = mysqli_fetch_assoc(mysqli_query($conn, $check_sql));
    
    $current_end = $row['subscription_end'];
    $today = date('Y-m-d');
    
    if ($current_end && $current_end > $today) {
        $start_date = $current_end; // Extend
    } else {
        $start_date = $today; // New Start
    }

    $new_end = date('Y-m-d', strtotime("$start_date +$duration_months months"));
    
    // Update DB
    $update_sql = "UPDATE members SET 
        subscription_start = IF(subscription_start IS NULL, '$today', subscription_start),
        subscription_end = '$new_end',
        plan_id = $plan_id,
        payment_status = 'active'
        WHERE id=$member_id";
        
    if (mysqli_query($conn, $update_sql)) {
        $success_msg = "Payment Successful! Membership extended to $new_end";
    } else {
        $error_msg = "Database Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-box {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            margin: 50px auto;
        }
        .form-control {
            background: rgba(0,0,0,0.2);
            border: 1px solid #444;
            color: white;
        }
        .form-control:focus {
            background: rgba(0,0,0,0.3);
            color: white;
            border-color: var(--primary-color);
            box-shadow: none;
        }
        .loader {
            display: none;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .payment-logo { height: 30px; margin: 0 5px; opacity: 0.8; }
        .nav-tabs .nav-link { color: #aaa; }
        .nav-tabs .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; border-color: transparent; }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container">
        
        <?php if(isset($success_msg)): ?>
            <div class="checkout-box text-center">
                <div class="display-1 mb-3">✅</div>
                <h2 class="text-success">Payment Successful!</h2>
                <p class="lead mb-4"><?= $success_msg ?></p>
                <a href="member_dashboard.php" class="btn btn-green">Go to Dashboard</a>
            </div>
        <?php else: ?>

            <div class="checkout-box" id="paymentForm">
                <h3 class="mb-4 border-bottom pb-3">Secure Checkout 🔒</h3>
                
                <div class="d-flex justify-content-between mb-4">
                    <span>Plan:</span>
                    <strong class="text-primary"><?= htmlspecialchars($plan_name) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Total:</span>
                    <strong class="fs-4">₹<?= htmlspecialchars(number_format($price)) ?></strong>
                </div>

                <ul class="nav nav-tabs mb-4 border-secondary" id="paymentTabs" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link active w-100" id="card-tab" data-bs-toggle="tab" data-bs-target="#card" type="button" role="tab">
                            💳 Card
                        </button>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link w-100" id="upi-tab" data-bs-toggle="tab" data-bs-target="#upi" type="button" role="tab">
                            📱 UPI
                        </button>
                    </li>
                </ul>

                <form method="POST" id="realForm">
                    <input type="hidden" name="payment_method" id="paymentMethod" value="Card">
                    
                    <div class="tab-content">
                        <!-- Card Payment -->
                        <div class="tab-pane fade show active" id="card" role="tabpanel">
                            <div class="mb-3 text-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" class="payment-logo">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="payment-logo">
                            </div>
                            <div class="mb-3">
                                <label>Card Number</label>
                                <input type="text" id="cardNum" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label>Expiry</label>
                                    <input type="text" id="cardExp" class="form-control" placeholder="MM/YY" maxlength="5">
                                </div>
                                <div class="col-6 mb-3">
                                    <label>CVV</label>
                                    <input type="password" id="cardCvv" class="form-control" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <!-- UPI Payment -->
                        <div class="tab-pane fade" id="upi" role="tabpanel">
                            <div class="mb-3 text-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/UPI-Logo-vector.svg/1280px-UPI-Logo-vector.svg.png" class="payment-logo bg-light rounded p-1">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_Pay_logo.svg" class="payment-logo bg-light rounded p-1">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" class="payment-logo bg-light rounded p-1">
                            </div>
                            <div class="mb-3">
                                <label>UPI ID</label>
                                <input type="text" id="upiId" class="form-control" placeholder="username@okhdfcbank">
                            </div>
                            <p class="text-muted small text-center">Open your UPI app and approve the request.</p>
                        </div>
                    </div>

                    <div class="loader" id="spinner"></div>

                    <div id="errorMsg" class="text-danger text-center mb-2" style="display:none;"></div>

                    <button type="button" onclick="processPayment()" class="btn btn-green w-100 py-2 fs-5 mt-2">Pay Securely</button>
                    <!-- Actual hidden submit -->
                    <input type="hidden" name="process" value="1">
                </form>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
    // Update Hidden Field on Tab Change
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (event) {
            document.getElementById('paymentMethod').value = event.target.id === 'card-tab' ? 'Card' : 'UPI';
            document.getElementById('errorMsg').style.display = 'none';
        })
    });

    function processPayment() {
        let method = document.getElementById('paymentMethod').value;
        let valid = true;
        let error = "";

        if (method === 'Card') {
            if (document.getElementById('cardNum').value.length < 10) { valid = false; error = "Invalid Card Number"; }
            else if (document.getElementById('cardExp').value.length < 4) { valid = false; error = "Invalid Expiry"; }
            else if (document.getElementById('cardCvv').value.length < 3) { valid = false; error = "Invalid CVV"; }
        } else {
            if (document.getElementById('upiId').value.length < 5 || !document.getElementById('upiId').value.includes('@')) { 
                valid = false; error = "Invalid UPI ID"; 
            }
        }

        if (!valid) {
            let el = document.getElementById('errorMsg');
            el.innerText = error;
            el.style.display = 'block';
            return;
        }

        // Mock Processing
        let btn = document.querySelector('.btn-green');
        let spinner = document.getElementById('spinner');
        
        btn.style.display = 'none';
        spinner.style.display = 'block';

        // Simulate Network Delay
        setTimeout(() => {
            document.getElementById('realForm').submit();
        }, 2500);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
