<?php
require_once __DIR__ . '/backend/db.php';

try {
    $pdo = getDB();
    
    // Check if columns exist, if not add them
    $columns = $pdo->query("SHOW COLUMNS FROM health_metrics LIKE 'diet_type'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE health_metrics ADD COLUMN diet_type ENUM('Veg', 'Non-Veg', 'Vegan') DEFAULT NULL");
        echo "Added diet_type column.<br>";
    }

    $columns = $pdo->query("SHOW COLUMNS FROM health_metrics LIKE 'diet_goal'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE health_metrics ADD COLUMN diet_goal ENUM('Weight Loss', 'Muscle Gain', 'Maintenance') DEFAULT NULL");
        echo "Added diet_goal column.<br>";
    }

    $columns = $pdo->query("SHOW COLUMNS FROM health_metrics LIKE 'food_allergies'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE health_metrics ADD COLUMN food_allergies TEXT DEFAULT NULL");
        echo "Added food_allergies column.<br>";
    }

    echo "Database schema updated successfully for Diet Preferences.";

} catch (PDOException $e) {
    die("DB Update Failed: " . $e->getMessage());
}
?>
