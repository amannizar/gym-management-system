<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";

$member_id = $_SESSION['member_id'];

// Mock Data Analysis (In a real app, query `workouts` table)
// Calculating consistency based on mock data for visual demo
$consistency_score = 85; // %
$workouts_this_week = 4;
$strength_gains = "+12%";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Report Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
<div class="page d-block">
    <div class="container py-4" id="report-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">📊 Your Activity Review</h2>
                <p class="text-muted mb-0">Detailed analysis & progression report.</p>
            </div>
            <button onclick="downloadPDF()" class="btn btn-warning fw-bold text-dark">
                Download Report 📄
            </button>
        </div>

        <div class="report-grid mb-4">
            
            <!-- Consistency Chart -->
            <div class="stat-card">
                <h4>🎯 Consistency</h4>
                <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                     <canvas id="consistencyChart"></canvas>
                </div>
            </div>

            <!-- Progression Line Chart -->
            <div class="stat-card">
                <h4>💪 Strength Progression</h4>
                <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                    <canvas id="progressionChart"></canvas>
                </div>
            </div>
            
        </div>

        <!-- Detailed Logs -->
        <div class="stat-card">
            <h4 class="mb-3">📝 Daily Activity Log</h4>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Workout</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Today, 25 Jan</td>
                            <td>Upper Body Power</td>
                            <td>60 mins</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>Yesterday, 24 Jan</td>
                            <td>Cardio & Abs</td>
                            <td>45 mins</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>23 Jan</td>
                            <td>Leg Day</td>
                            <td>75 mins</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>22 Jan</td>
                            <td>Rest Day</td>
                            <td>-</td>
                            <td><span class="badge bg-secondary">Rest</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4 no-print">
            <a href="member_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

    </div>
</div>

<script>
    // --- Charts Logic ---
    const ctx1 = document.getElementById('consistencyChart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Missed'],
            datasets: [{
                data: [5, 2],
                backgroundColor: ['#00ffa3', '#333'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: { legend: { position: 'bottom', labels: { color: 'white' } } }
        }
    });

    const ctx2 = document.getElementById('progressionChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Weight Lifted (Avg Kg)',
                data: [60, 65, 68, 72],
                borderColor: '#2563eb',
                tension: 0.4,
                pointBackgroundColor: '#fff'
            }]
        },
        options: {
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa' } },
                x: { grid: { display: false }, ticks: { color: '#aaa' } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // --- PDF Logic ---
    function downloadPDF() {
        const element = document.getElementById('report-content');
        const opt = {
            margin: 10,
            filename: 'My_Gym_Report.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
</body>
</html>
