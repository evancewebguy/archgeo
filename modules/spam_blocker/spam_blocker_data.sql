SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `spam_blocker_data` (
  `id` int(11) NOT NULL,
  `element` varchar(255) DEFAULT NULL,
  `target_string` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `spam_blocker_data`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `spam_blocker_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;