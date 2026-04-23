<?php
include("db_connection.php");

if (!isset($_GET['query']) || trim($_GET['query']) === '') {
    header("Location: products.php");
    exit;
}

$query = strtolower(trim($_GET['query']));

// Keyword-to-section mapping
$map = [
    "cleanser" => "cleansers",
    "face wash" => "cleansers",
    "toner" => "toners",
    "serum" => "serums",
    "moisturizer" => "moisturizers",
    "moisturiser" => "moisturizers",
    "cream" => "moisturizers",
    "sunscreen" => "sunscreens",
    "spf" => "sunscreens"
];

// Step 1: Direct keyword mapping to section ID
foreach ($map as $keyword => $sectionId) {
    if (strpos($query, $keyword) !== false) {
        header("Location: products.php#$sectionId");
        exit;
    }
}

// Step 2: Optional fallback — check DB for product category
$stmt = $conn->prepare("
    SELECT category 
    FROM products 
    WHERE LOWER(product_name) LIKE CONCAT('%', ?, '%') 
       OR LOWER(category) LIKE CONCAT('%', ?, '%') 
    LIMIT 1
");
$stmt->bind_param("ss", $query, $query);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($matchedCategory);
    $stmt->fetch();

    // Convert category to section ID
    $categoryToSectionId = [
        "Cleanser" => "cleansers",
        "Toner" => "toners",
        "Serum" => "serums",
        "Moisturizer" => "moisturizers",
        "Sunscreen" => "sunscreens"
    ];

    if (isset($categoryToSectionId[$matchedCategory])) {
        $sectionId = $categoryToSectionId[$matchedCategory];
        header("Location: products.php#$sectionId");
        exit;
    }
}

// Step 3: No match found
echo "<script>
    alert('No results found. Try Cleanser, Toner, Serum, Moisturizer, or Sunscreen.');
    window.location.href = 'products.php';
</script>";
exit;
?>
