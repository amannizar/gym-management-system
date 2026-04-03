<?php
// ==========================================
// 🎓 CONCEPT: CLASS vs OBJECT
// ==========================================

// 1. CLASS (The Blueprint)
// Think of a Class like a "Form" or "Template".
// It defines what data (properties) and actions (methods) a thing should have.
class GymMember {
    
    // Properties (Data)
    // Every member has these, but the *values* will be different for each person.
    public $name;
    public $weight_kg;
    public $membership_type;

    // Constructor (The Setup)
    // This runs automatically when we create a NEW object.
    public function __construct($name, $weight, $type) {
        $this->name = $name;
        $this->weight_kg = $weight;
        $this->membership_type = $type;
    }

    // Method (Action)
    // What can a member do? They can workout!
    public function doWorkout() {
        $this->weight_kg -= 0.5; // Burn calories, lose weight
        return "{$this->name} is lifting weights! 💪 (New Weight: {$this->weight_kg}kg)";
    }

    // Another Method
    public function eatProtein() {
        $this->weight_kg += 0.2; // Gain muscle mass
        return "{$this->name} drank a protein shake. 🥤";
    }

    // Display Info
    public function getCard() {
        return "[ MEMBER CARD ] Name: {$this->name} | Plan: {$this->membership_type}";
    }
}

// ==========================================
// 2. OBJECT (The Real Thing)
// An Object is a specific "Copy" created from the Class.
// ==========================================

// Creating Object 1: "John"
$member1 = new GymMember("John Doe", 80, "Gold");

// Creating Object 2: "Jane"
$member2 = new GymMember("Jane Smith", 65, "Silver");

// Let's see them in action
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learn OOP: Class vs Object</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #1a1a1a; color: white; }</style>
</head>
<body class="container py-5">
    <h1 class="text-warning mb-4">🎓 Class vs Object Tutorial</h1>

    <div class="row">
        <!-- Object 1 -->
        <div class="col-md-6">
            <div class="card bg-dark border-primary mb-3">
                <div class="card-header bg-primary text-white">Object 1 ($member1)</div>
                <div class="card-body">
                    <h5><?= $member1->getCard() ?></h5>
                    <hr>
                    <p class="text-info"><?= $member1->doWorkout() ?></p>
                    <p class="text-success"><?= $member1->eatProtein() ?></p>
                </div>
            </div>
        </div>

        <!-- Object 2 -->
        <div class="col-md-6">
            <div class="card bg-dark border-success mb-3">
                <div class="card-header bg-success text-white">Object 2 ($member2)</div>
                <div class="card-body">
                    <h5><?= $member2->getCard() ?></h5>
                    <hr>
                    <p class="text-info"><?= $member2->doWorkout() ?></p>
                    <p class="text-info"><?= $member2->doWorkout() ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 p-3 bg-secondary rounded">
        <h4>Summary:</h4>
        <ul>
            <li><strong>Class (GymMember)</strong>: The code that defines properties ($name) and functions (doWorkout). It's just lines of code.</li>
            <li><strong>Object ($member1, $member2)</strong>: The actual data in memory created using <code>new GymMember()</code>. Each object has its own separate data (John vs Jane).</li>
        </ul>
        <a href="dashboard.php" class="btn btn-light mt-2">Back to Dashboard</a>
    </div>
</body>
</html>
