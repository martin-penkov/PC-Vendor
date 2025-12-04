<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';

$conn = getDBConnection();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Невалиден метод на заявка.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        $response['message'] = 'Невалидно ID на продукт.';
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

    // DELETE операция с prepared statement
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Грешка при подготовка на заявката: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Продуктът е изтрит успешно!';
    } else {
        throw new Exception('Грешка при изпълнение на заявката: ' . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    $response['message'] = 'Грешка при изтриване на продукта: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>

