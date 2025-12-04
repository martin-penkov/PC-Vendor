<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$conn = getDBConnection();
$response = ['success' => false, 'data' => [], 'message' => ''];

try {
    $whereConditions = [];
    $params = [];
    $types = '';

    // SELECT WHERE - по ID
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = intval($_GET['id']);
        $whereConditions[] = "id = ?";
        $params[] = $id;
        $types .= 'i';
    }

    // SELECT WHERE - по категория
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category = sanitizeInput($_GET['category'], $conn);
        $whereConditions[] = "category = ?";
        $params[] = $category;
        $types .= 's';
    }

    // SELECT WHERE - търсене по име
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = sanitizeInput($_GET['search'], $conn);
        $whereConditions[] = "(name LIKE ? OR description LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }

    // SELECT WHERE - минимална цена
    if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
        $minPrice = floatval($_GET['min_price']);
        $whereConditions[] = "price >= ?";
        $params[] = $minPrice;
        $types .= 'd';
    }

    // SELECT WHERE - максимална цена
    if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $maxPrice = floatval($_GET['max_price']);
        $whereConditions[] = "price <= ?";
        $params[] = $maxPrice;
        $types .= 'd';
    }

    // SELECT WHERE - минимална наличност
    if (isset($_GET['min_stock']) && !empty($_GET['min_stock'])) {
        $minStock = intval($_GET['min_stock']);
        $whereConditions[] = "stock >= ?";
        $params[] = $minStock;
        $types .= 'i';
    }

    // Изграждане на заявката
    $sql = "SELECT * FROM products";
    
    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(" AND ", $whereConditions);
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    // LIMIT за ограничаване на резултатите
    if (isset($_GET['limit']) && !empty($_GET['limit'])) {
        $limit = intval($_GET['limit']);
        $sql .= " LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
    }

    // Използване на prepared statements за защита
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $products;
    $response['message'] = 'Продуктите са заредени успешно.';

} catch (Exception $e) {
    $response['message'] = 'Грешка при извличане на продукти: ' . $e->getMessage();
}

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>

