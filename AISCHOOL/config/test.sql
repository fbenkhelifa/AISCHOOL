Use aischool;

DESCRIBE chat_sessions;
CREATE TABLE chat_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    messages LONGTEXT NOT NULL
);