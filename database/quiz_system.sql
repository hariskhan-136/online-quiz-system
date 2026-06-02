-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin', 'user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Insert sample users
-- Explicitly requested plain text passwords for learning purposes.
INSERT INTO `users` (`name`, `email`, `password`, `role`)
VALUES (
    'Haris (Admin)',
    'admin@quiz.com',
    'haris123',
    'admin'
  ),
  ('Haris (User)', 'haris', 'haris123', 'user'),
  ('Admin User', 'admin', 'haris123', 'admin');
-- Table structure for table `questions`
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Insert sample questions
INSERT INTO `questions` (
    `question`,
    `option_a`,
    `option_b`,
    `option_c`,
    `option_d`,
    `correct_answer`
  )
VALUES (
    'What is HTML?',
    'Programming language',
    'HyperText Markup Language',
    'Database',
    'Operating system',
    'B'
  ),
  (
    'What does PHP stand for?',
    'Personal Home Page',
    'Private Home Protocol',
    'Program Hypertext Page',
    'None',
    'A'
  ),
  (
    'Which is a database?',
    'HTML',
    'CSS',
    'MySQL',
    'Java',
    'C'
  ),
  (
    'Which is used for styling?',
    'CSS',
    'PHP',
    'SQL',
    'Python',
    'A'
  ),
  (
    'Which is backend language?',
    'HTML',
    'CSS',
    'PHP',
    'Photoshop',
    'C'
  );
-- Table structure for table `results`
CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_results_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;