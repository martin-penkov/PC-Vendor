CREATE DATABASE IF NOT EXISTS computer_parts_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE computer_parts_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (name, category, price, description, stock, image) VALUES
('Intel Core i7-13700K', 'Процесор', 589.99, 'Процесор Intel Core i7-13700K, 16 ядра, 24 нишки, 3.4 GHz базова честота', 15, 'cpu_intel_i7.jpg'),
('AMD Ryzen 9 7900X3D', 'Процесор', 649.99, 'Процесор AMD Ryzen 9 7900X3D, 12 ядра, 24 нишки, 4.7 GHz базова честота', 10, 'cpu_amd_ryzen9.jpg'),
('NVIDIA RTX 4080', 'Видео карта', 1299.99, 'Видео карта NVIDIA GeForce RTX 4080, 16GB GDDR6X', 8, 'gpu_rtx4080.jpg'),
('AMD Radeon RX 7900 XTX', 'Видео карта', 999.99, 'Видео карта AMD Radeon RX 7900 XTX, 24GB GDDR6', 12, 'gpu_rx7900.jpg'),
('Corsair Vengeance 32GB DDR5', 'Памет', 249.99, 'Памет Corsair Vengeance 32GB (2x16GB) DDR5-5600MHz', 25, 'ram_corsair.jpg'),
('Samsung 980 PRO 1TB', 'Твърд диск', 129.99, 'SSD диск Samsung 980 PRO 1TB NVMe M.2 PCIe 4.0', 30, 'ssd_samsung.jpg'),
('ASUS ROG Strix B650E-F', 'Дънна платка', 349.99, 'Дънна платка ASUS ROG Strix B650E-F Gaming WiFi, AMD AM5', 18, 'mobo_asus.jpg');

