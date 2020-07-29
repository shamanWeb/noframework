CREATE TABLE `user`(
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30),
  password VARCHAR(32),
  hash VARCHAR(32),
  balance DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO `user` (`username`, `password`, `balance`) VALUES ('user', '696d29e0940a4957748fe3fc9efd22a3', 666.66); /* password = password */

CREATE TABLE `account`(
  id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  user_id int,
  datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
  value DECIMAL(10,2),
  hash VARCHAR(32),
  status ENUM("new", "process", "success", "failed") DEFAULT "new",
  FOREIGN KEY fk_cat(user_id)
  REFERENCES user(id)
  ON UPDATE CASCADE
  ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO `account` (`user_id`, `datetime`, `value`, `hash`, `status`) VALUES (1, NOW(), 666.66, '696d29e0940a4957748fe3fc9efd22a3', 'success');