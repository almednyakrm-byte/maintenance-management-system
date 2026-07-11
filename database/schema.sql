CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
);

CREATE TABLE فواتير (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  invoice_date DATE NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user_id (user_id)
);

CREATE TABLE جداول_التقدم (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  progress_date DATE NOT NULL,
  progress_percentage INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user_id (user_id)
);

CREATE TABLE مراقبة_الوقت (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  time_log_date DATE NOT NULL,
  hours_worked DECIMAL(5, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user_id (user_id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin'),
  ('user1', 'user1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'user'),
  ('guest1', 'guest1@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'guest');

INSERT INTO فواتير (user_id, invoice_date, total) VALUES
  (1, '2022-01-01', 100.00),
  (2, '2022-01-15', 200.00),
  (3, '2022-02-01', 50.00);

INSERT INTO جداول_التقدم (user_id, progress_date, progress_percentage) VALUES
  (1, '2022-01-01', 50),
  (2, '2022-01-15', 75),
  (3, '2022-02-01', 25);

INSERT INTO مراقبة_الوقت (user_id, time_log_date, hours_worked) VALUES
  (1, '2022-01-01', 8.00),
  (2, '2022-01-15', 7.50),
  (3, '2022-02-01', 6.00);