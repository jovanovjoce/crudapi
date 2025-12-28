CREATE TABLE task (
                      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                      title VARCHAR(255) NOT NULL,
                      description TEXT,
                      status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
                      priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
                      due_date DATE,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
