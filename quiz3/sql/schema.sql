CREATE TABLE site_visits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_path VARCHAR(255) NOT NULL,
  page_title VARCHAR(255) NULL,
  referrer VARCHAR(255) NULL,
  user_agent VARCHAR(255) NULL,
  ip_hash CHAR(64) NULL,
  visited_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_visited_at (visited_at),
  INDEX idx_page_path (page_path)
);

