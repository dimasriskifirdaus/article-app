# article-app
To publish articles and comment on it

# database-prep
CREATE TABLE Articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    question_text TEXT,
    user_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES Articles(id)
);

CREATE TABLE Answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    answer_text TEXT,
    user_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES Questions(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    content TEXT NOT NULL,
    parent_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES Articles(id) ON DELETE CASCADE
);
