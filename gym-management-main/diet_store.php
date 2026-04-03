<?php
require_once "backend/member_nav.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Eats - Healthy Food Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding-bottom: 50px;
        }
        .food-card {
            background: var(--card-bg);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.2s;
        }
        .food-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }
        .food-img {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }
        .food-body {
            padding: 15px;
        }
        .food-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }
        .food-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .food-price {
            font-size: 1.2rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .category-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .cat-chip {
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 50px;
            white-space: nowrap;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            color: white;
        }
        .cat-chip:hover, .cat-chip.active {
            background: var(--primary-color);
            color: black;
        }
    </style>
</head>
<body>
<div class="page d-block">
    <div class="container py-4">
        
        <h2 class="mb-4">Gym Eats 🥗</h2>
        
        <!-- Categories -->
        <div class="category-scroll">
            <a href="#" class="cat-chip active">All</a>
            <a href="#" class="cat-chip">High Protein</a>
            <a href="#" class="cat-chip">Keto Friendly</a>
            <a href="#" class="cat-chip">Pre-Workout</a>
            <a href="#" class="cat-chip">Smoothies</a>
        </div>

        <!-- Food List -->
        <div class="food-grid">
            
            <!-- Item 1 -->
            <div class="food-card">
                <div style="position:relative;">
                    <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=1413&auto=format&fit=crop" class="food-img">
                    <span class="badge bg-success" style="position:absolute; top:10px; right:10px;">🟢 Available Now</span>
                </div>
                <div class="food-body">
                    <div class="food-title">Grilled Chicken Salad</div>
                    <div class="text-warning mb-2" style="font-size:0.8rem;">📍 1.2km away • 🕒 20 mins</div>
                    <div class="food-meta">
                        <span>🍗 45g Protein</span>
                        <span>🔥 350 kcal</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="food-price">₹250</div>
                        <button onclick="orderItem('Grilled Chicken Salad')" class="btn btn-sm btn-outline-light">Add +</button>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="food-card">
                <div style="position:relative;">
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1480&auto=format&fit=crop" class="food-img"> <!-- FIXED URL -->
                    <span class="badge bg-success" style="position:absolute; top:10px; right:10px;">🟢 Available Now</span>
                </div>
                <div class="food-body">
                    <div class="food-title">Keto Avocado Bowl</div>
                    <div class="text-warning mb-2" style="font-size:0.8rem;">📍 0.8km away • 🕒 15 mins</div>
                    <div class="food-meta">
                        <span>🥑 30g Fat</span>
                        <span>🔥 420 kcal</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="food-price">₹180</div>
                        <button onclick="orderItem('Keto Avocado Bowl')" class="btn btn-sm btn-outline-light">Add +</button>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="food-card">
                <div style="position:relative;">
                    <img src="https://images.unsplash.com/photo-1579722820308-d74e571900a9?q=80&w=1480&auto=format&fit=crop" class="food-img"> <!-- FIXED URL -->
                    <span class="badge bg-warning text-dark" style="position:absolute; top:10px; right:10px;">🟠 5 Left</span>
                </div>
                <div class="food-body">
                    <div class="food-title">Whey Protein Shake</div>
                    <div class="text-warning mb-2" style="font-size:0.8rem;">📍 In Gym Cafe • ⚡ 5 mins</div>
                    <div class="food-meta">
                        <span>🥤 25g Protein</span>
                        <span>🔥 120 kcal</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="food-price">₹100</div>
                        <button onclick="orderItem('Whey Protein Shake')" class="btn btn-sm btn-outline-light">Add +</button>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="food-card">
                <div style="position:relative;">
                    <img src="https://images.unsplash.com/photo-1525351484163-7529414395d8?q=80&w=1480&auto=format&fit=crop" class="food-img"> <!-- FIXED URL -->
                    <span class="badge bg-success" style="position:absolute; top:10px; right:10px;">🟢 Available Now</span>
                </div>
                <div class="food-body">
                    <div class="food-title">Peanut Butter Toast</div>
                    <div class="text-warning mb-2" style="font-size:0.8rem;">📍 2.5km away • 🕒 30 mins</div>
                    <div class="food-meta">
                        <span>🥜 High Carb</span>
                        <span>🔥 290 kcal</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="food-price">₹120</div>
                        <button onclick="orderItem('Peanut Butter Toast')" class="btn btn-sm btn-outline-light">Add +</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: #222; color: white;">
      <div class="modal-body text-center py-4">
        <div style="font-size: 3rem;">🎉</div>
        <h4 class="mt-2">Order Placed!</h4>
        <p class="text-muted" id="orderText">Your food is being prepared.</p>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Awesome</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function orderItem(name) {
        document.getElementById('orderText').innerText = 'Your ' + name + ' will be ready in 15 mins!';
        var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
        myModal.show();
    }
</script>
</body>
</html>
