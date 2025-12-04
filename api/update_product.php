<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$conn = getDBConnection();
$response = ['success' => false, 'message' => '', 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Невалиден метод на заявка.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Валидация и екраниране
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name'], $conn) : '';
    $category = isset($_POST['category']) ? sanitizeInput($_POST['category'], $conn) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $description = isset($_POST['description']) ? sanitizeInput($_POST['description'], $conn) : '';
    $image = isset($_POST['image']) ? sanitizeInput($_POST['image'], $conn) : '';

    $errors = [];

    // Валидация
    if ($id <= 0) {
        $errors['id'] = 'Невалидно ID на продукт.';
    }

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

    // Проверка дали продуктът съществува
    $checkSql = "SELECT id FROM products WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('i', $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        $response['message'] = 'Продуктът не е намерен.';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        $checkStmt->close();
        $conn->close();
        exit;
    }
    $checkStmt->close();

    // UPDATE операция с prepared statement
    $sql = "UPDATE products SET name = ?, category = ?, price = ?, description = ?, stock = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Грешка при подготовка на заявката: ' . $conn->error);
    }
    
    $stmt->bind_param('ssdsisi', $name, $category, $price, $description, $stock, $image, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Продуктът е обновен успешно!';
        } else {
            $response['message'] = 'Няма промени за запазване.';
        }
    } else {
        throw new Exception('Грешка при изпълнение на заявката: ' . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Грешка при обновяване на продукта: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>

