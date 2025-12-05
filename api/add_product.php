<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

$conn = getDBConnection();
$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Невалиден метод на заявка.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name'], $conn) : '';
    $category = isset($_POST['category']) ? sanitizeInput($_POST['category'], $conn) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $description = isset($_POST['description']) ? sanitizeInput($_POST['description'], $conn) : '';
    $image = isset($_POST['image']) ? sanitizeInput($_POST['image'], $conn) : '';

    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Името на продукта е задължително.';
    } elseif (strlen($name) > 255) {
        $errors['name'] = 'Името е твърде дълго (максимум 255 символа).';
    }

    if (empty($category)) {
        $errors['category'] = 'Категорията е задължителна.';
    }

    if ($price <= 0) {
        $errors['price'] = 'Цената трябва да е положително число.';
    }

    if ($stock < 0) {
        $errors['stock'] = 'Наличността не може да е отрицателна.';
    }

    if (!empty($errors)) {
        $response['errors'] = $errors;
        $response['message'] = 'Има грешки при валидацията.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $conn->close();
        exit;
    }

    $sql = "INSERT INTO products (name, category, price, description, stock, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Грешка при подготовка на заявката: ' . $conn->error);
    }
    
    $stmt->bind_param('ssdsis', $name, $category, $price, $description, $stock, $image);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Продуктът е добавен успешно!';
        $response['id'] = $conn->insert_id;
    } else {
        throw new Exception('Грешка при изпълнение на заявката: ' . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Грешка при добавяне на продукта: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
