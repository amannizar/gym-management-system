<?php
require_once "backend/member_nav.php";
require_once "backend/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Devices - GymPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .page-iot {
            background: radial-gradient(circle at center, #1a1a1a 0%, #000 100%);
            min-height: 100vh;
            padding-top: 100px; /* Nav spacing */
        }
        
        .device-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .device-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 0 20px rgba(0, 255, 163, 0.1);
        }

        .device-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-right: 20px;
        }

        .device-info h5 { margin: 0; font-weight: 600; color: white; }
        .device-info small { color: #888; }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-live {
            background: rgba(0, 255, 163, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(0, 255, 163, 0.3);
        }

        .status-offline {
            background: rgba(255, 77, 77, 0.1);
            color: #ff4d4d;
            border: 1px solid rgba(255, 77, 77, 0.3);
        }

        /* Pulsing Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--primary-color);
            border-radius: 50%;
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 255, 163, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 255, 163, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 255, 163, 0); }
        }

        /* Radar Scan Animation */
        .radar-container {
            width: 200px;
            height: 200px;
            border: 2px solid rgba(0, 255, 163, 0.3);
            border-radius: 50%;
            position: relative;
            margin: 40px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, rgba(0,255,163,0.1) 0%, rgba(0,0,0,0) 70%);
        }

        .radar-sweep {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border-radius: 50%;
            background: conic-gradient(from 0deg, transparent 0deg, rgba(0, 255, 163, 0.4) 30deg, transparent 30deg);
            animation: radar-spin 2s linear infinite;
        }

        .radar-blip {
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            top: 30%;
            left: 70%;
            box-shadow: 0 0 10px white;
            animation: blip 2s infinite;
        }

        @keyframes radar-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes blip { 0% { opacity: 0; } 50% { opacity: 1; } 100% { opacity: 0; } }

        .scan-text {
            text-align: center;
            color: var(--primary-color);
            font-family: 'Courier New', monospace;
            margin-top: -20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="page-iot">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <h2 class="mb-4 text-center">Connected Devices 📡</h2>

                <!-- Radar Scanner Visual -->
                <div class="radar-container">
                    <div class="radar-sweep"></div>
                    <div class="radar-blip"></div>
                    <span style="font-size: 3rem;">⌚</span>
                </div>
                <div class="scan-text">SCANNING FOR SIGNALS...</div>

                <!-- Device 1 -->
                <div class="device-card">
                    <div class="d-flex align-items-center">
                        <div class="device-icon">⌚</div>
                        <div class="device-info">
                            <h5>Apple Watch Series 7</h5>
                            <small>Synced: Just now</small>
                        </div>
                    </div>
                    <div class="status-badge status-live">
                        <div class="pulse-dot"></div> LIVE
                    </div>
                </div>

                <!-- Device 2 -->
                <div class="device-card">
                    <div class="d-flex align-items-center">
                        <div class="device-icon">💓</div>
                        <div class="device-info">
                            <h5>Polar H10 Chest Strap</h5>
                            <small>Synced: 2 hours ago</small>
                        </div>
                    </div>
                    <div class="status-badge status-offline">
                        ⭕ OFFLINE
                    </div>
                </div>

                <!-- Device 3 (New) -->
                <div class="device-card" style="opacity: 0.7;">
                    <div class="d-flex align-items-center">
                        <div class="device-icon">👟</div>
                        <div class="device-info">
                            <h5>Nike Smart Shoes</h5>
                            <small>Not Detected</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary rounded-pill">Connect</button>
                </div>

                <div class="text-center mt-5">
                    <button class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg">Manual Scan</button>
                    <br>
                    <a href="member_dashboard.php" class="btn btn-link text-white-50 mt-3">Back to Dashboard</a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
