<?php
require_once "backend/auth_check.php";
require_once "backend/db.php";
require_once "includes/header.php";

// Fetch basic stats
$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM members"))['count'];
$active_attendance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT member_id) as count FROM attendance WHERE date = CURDATE()"))['count'];
?>

<div class="main-wrapper">
    <?php require_once "includes/sidebar.php"; ?>
    <?php require_once "includes/topbar.php"; ?>
    
    <div class="main-content">
        <div class="page_header">
            <h1 class="page_title">Gym Analytics</h1>
        </div>

        <div class="row g-4 mb-4">
             <div class="col-md-6 col-lg-3">
                <div class="card p-3 mb-0 text-center">
                    <h5 class="text-muted mb-1">Daily Traffic</h5>
                    <h2 class="mb-0 text-primary"><?= $active_attendance ?></h2>
                </div>
             </div>
             <div class="col-md-6 col-lg-3">
                <div class="card p-3 mb-0 text-center">
                    <h5 class="text-muted mb-1">Total Members</h5>
                    <h2 class="mb-0 text-white"><?= $total_members ?></h2>
                </div>
             </div>
             <div class="col-md-6 col-lg-3">
                <div class="card p-3 mb-0 text-center">
                    <h5 class="text-muted mb-1">New this Month</h5>
                    <h2 class="mb-0 text-success">+12</h2>
                </div>
             </div>
             <div class="col-md-6 col-lg-3">
                <div class="card p-3 mb-0 text-center">
                    <h5 class="text-muted mb-1">Retention Rate</h5>
                    <h2 class="mb-0 text-info">94%</h2>
                </div>
             </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">Revenue Overview (Yearly)</div>
                    <div class="card-body">
                         <canvas id="revenueChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">Membership Distribution</div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="membershipChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (₹)',
                data: [120000, 150000, 180000, 200000, 210000, 190000, 220000, 240000, 250000, 270000, 290000, 310000],
                borderColor: '#00d287',
                backgroundColor: 'rgba(0, 210, 135, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: '#8b92a5' } }
            },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#8b92a5' } },
                x: { grid: { display: false }, ticks: { color: '#8b92a5' } }
            }
        }
    });

    // Membership Chart
    const ctxMember = document.getElementById('membershipChart').getContext('2d');
    new Chart(ctxMember, {
        type: 'doughnut',
        data: {
            labels: ['Yearly', 'Monthly', 'VIP'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#4f46e5', '#00d287', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#8b92a5' } }
            }
        }
    });
</script>

<?php require_once "includes/footer.php"; ?>
