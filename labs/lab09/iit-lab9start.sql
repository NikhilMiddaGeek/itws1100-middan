-- Lab 09 starter schema (rubric-aligned)
-- Import this into the `iit` database in phpMyAdmin.

CREATE TABLE IF NOT EXISTS actors (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first VARCHAR(255) NOT NULL,
  last VARCHAR(255) NOT NULL,
  birth_date DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS movies (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  `year` INT NOT NULL
);

CREATE TABLE IF NOT EXISTS movie_actors (
  movie_id INT NOT NULL,
  actor_id INT NOT NULL,
  PRIMARY KEY (movie_id, actor_id),
  CONSTRAINT fk_movie_actors_movie FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
  CONSTRAINT fk_movie_actors_actor FOREIGN KEY (actor_id) REFERENCES actors(id) ON DELETE CASCADE
);

