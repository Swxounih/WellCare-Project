<?php
session_start();
include(__DIR__ . '/config/db_connection.php');

// Check if user is admin or just run the insert
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_products'])) {
    try {
        // First, insert categories with images
        $categories = [
            [1, 'Medicines & Treatments', 'Over-the-counter medicines and pain relief', 'Medicine.jpg'],
            [2, 'Baby Kids', 'Baby care products and children wellness', 'BabyKids.jpg'],
            [3, 'Personal Care', 'Skincare and personal hygiene products', 'PersonalCare.jpg'],
            [4, 'Medical Supplies', 'Medical equipment and first aid supplies', 'MedicalSupp.jpg']
        ];

        foreach ($categories as $cat) {
            $stmt = $conn->prepare("INSERT INTO categories (category_id, name, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $cat[0], $cat[1], $cat[2], $cat[3]);
            $stmt->execute();
        }

        // Insert products with actual image files from your assets
        $products = [
            ['Paracetamol Biogesic 500mg Tablet', 'Paracetamol Biogesic 500mg 10 Tablets - Everyday Pain and Fever Support', 108.00, 150, 'Product.png', 1],
            ['Paracetamol Biogesic Syrup 60ml', 'Paracetamol Biogesic for Kids 250mg/5ml Syrup 60ml - Melon Flavor', 156.00, 120, 'Product2.jpg', 1],
            ['Neozep Forte Tablet', 'Neozep Forte 10 Tablet Strips - Multi-Symptom Relief for Colds, Fever & Flu', 120.00, 200, 'Product3.png', 1],
            ['Bioflu Tablet 100s', 'Bioflu Phenylephrine HCl 100 Film-Coated Tablets - Cold & Flu Relief', 185.00, 100, 'Product4.png', 1],
            ['Fern-C Gold 30 Capsules', 'Fern-C Gold 27+3 Pack - Vitamin C with Cholecalciferol & Zinc Capsules', 371.25, 180, 'Product5.jpg', 1],
            ['Cetaphil Baby Wash 400ml', 'Cetaphil Baby Moisturizing Bath & Wash 400ml - For Sensitive Skin', 606.00, 75, 'Product6.jpg', 2],
            ['NIVEA Creme 75ml', 'NIVEA Creme 75ml - All-Purpose Moisturizer for Dry Skin', 125.00, 200, 'Product.png', 3],
            ['NIVEA Body Lotion 400ml', 'NIVEA In-Shower Body Lotion 400ml - Moisturizes While Showering', 195.00, 150, 'Product2.jpg', 3],
            ['NIVEA Soft Cream 200ml', 'NIVEA Soft Cream 200ml - Light Moisturizer for All Skin Types', 155.00, 120, 'Product3.png', 3],
            ['Medical Oxygen Tank 10L', 'Portable Oxygen Tank 10 Liters with Regulator - For Emergency Use', 1850.00, 30, 'Product4.png', 4],
            ['Blood Pressure Monitor Digital', 'Automatic Digital Blood Pressure Monitor - Easy Home Monitoring', 899.00, 45, 'Product5.jpg', 4],
            ['First Aid Kit Complete', 'Complete First Aid Kit with Medical Supplies and Emergency Essentials', 450.00, 60, 'Product6.jpg', 4],
            ['Tramadol HCl Tablets 50mg', 'Tramadol Hydrochloride 50mg Tablets - Pain Relief (Prescription Required)', 280.00, 40, 'Product.png', 1],
            ['Amoxicillin Capsule 500mg', 'Amoxicillin 500mg Capsules - Antibiotic (Prescription Required)', 195.00, 50, 'Product2.jpg', 1],
            ['Cough Syrup 120ml', 'Cough Syrup 120ml - Effective Relief from Dry & Wet Cough', 89.50, 100, 'Product3.png', 1],
            ['Multivitamin Plus 30 Tablets', 'Multivitamin Plus Iron & Calcium 30 Tablets - Daily Supplement', 245.00, 180, 'Product4.png', 1],
            ['Aspirin 100 Tablets', 'Aspirin 500mg 100 Tablets - For Headache & Pain Relief', 125.00, 200, 'Product5.jpg', 1],
            ['Antacid Tablets 20s', 'Antacid Tablets 20 Pieces - Quick Relief from Heartburn', 45.00, 250, 'Product6.jpg', 1],
            ['Vitamin D3 Soft Gels 60s', 'Vitamin D3 1000 IU 60 Soft Gels - Bone & Immune Support', 325.00, 150, 'Product.png', 1],
            ['Skin Care Essentials Bundle', 'Complete skin care package with moisturizers and cleansers', 285.00, 95, 'Product2.jpg', 3]
        ];

        $count = 0;
        foreach ($products as $product) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiis", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
            if ($stmt->execute()) {
                $count++;
            }
        }

        $success = true;
        $message = "✅ Successfully inserted $count products and categories into the database!";
    } catch (Exception $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Seeder - Well Care Pharmacy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #2e8b57 0%, #4caf7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #2e8b57;
            margin-bottom: 15px;
            font-size: 28px;
        }
        .subtitle {
            color: #777;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .info-box {
            background: #f0faf5;
            border-left: 4px solid #2e8b57;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 600;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn {
            background: linear-gradient(135deg, #2e8b57 0%, #1a5c38 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 139, 87, 0.3);
        }
        .btn:active {
            transform: translateY(0);
        }
        .product-list {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .product-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-item strong {
            color: #2e8b57;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏥 Product Seeder</h1>
        <p class="subtitle">Insert sample products into your Well Care Pharmacy database</p>

        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <strong>📝 What this does:</strong><br>
            • Inserts 4 product categories with images<br>
            • Inserts 20 pharmacy products<br>
            • Uses images from your assets folders:<br>
            &nbsp;&nbsp;&nbsp;- CategoryImage folder (Medicine.jpg, BabyKids.jpg, etc.)<br>
            &nbsp;&nbsp;&nbsp;- ProductsImage folder (Product.png, Product2.jpg, etc.)<br>
            • Products include medicines, baby care, skincare, and medical supplies
        </div>

        <div class="product-list">
            <strong style="color: #2e8b57;">Products to be inserted:</strong>
            <div class="product-item">1. <strong>Paracetamol Biogesic 500mg Tablet</strong> - ₱108.00</div>
            <div class="product-item">2. <strong>Paracetamol Biogesic Syrup</strong> - ₱156.00</div>
            <div class="product-item">3. <strong>Neozep Forte Tablet</strong> - ₱120.00</div>
            <div class="product-item">4. <strong>Bioflu Tablet 100s</strong> - ₱185.00</div>
            <div class="product-item">5. <strong>Fern-C Gold 30 Capsules</strong> - ₱371.25</div>
            <div class="product-item">6. <strong>Cetaphil Baby Wash</strong> - ₱606.00</div>
            <div class="product-item">7. <strong>NIVEA Creme 75ml</strong> - ₱125.00</div>
            <div class="product-item">8. <strong>NIVEA Body Lotion</strong> - ₱195.00</div>
            <div class="product-item">9. <strong>NIVEA Soft Cream</strong> - ₱155.00</div>
            <div class="product-item">10. <strong>Medical Oxygen Tank</strong> - ₱1,850.00</div>
            <div class="product-item">... and 10 more products</div>
        </div>

        <form method="POST">
            <button type="submit" name="insert_products" class="btn">
                🚀 Insert Products Now
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; color: #777; font-size: 13px;">
            <p>After inserting, go to: <strong>http://localhost/WellCare%20Project/index.php</strong></p>
        </div>
    </div>
</body>
</html>
