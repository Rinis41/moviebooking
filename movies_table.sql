USE auth_demo;

CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    img VARCHAR(255) NOT NULL,
    genres VARCHAR(255) NOT NULL,
    imdb VARCHAR(10) NOT NULL
);

-- Example data
INSERT INTO movies (title, img, genres, imdb) VALUES
('Fight Club', 'images/fightclub.png', 'Drama,Thriller', '8.8'),
('Oppenheimer', 'images/oppenheimer.jpg', 'Drama,History', '8.4'),
('Godzilla Minus One', 'images/godzilla.png', 'Action,Sci-Fi', '8.0'),
('Scarface', 'images/scarface.png', 'Crime,Drama', '8.3'),
('Interstellar', 'images/interstellar.png', 'Sci-Fi,Drama', '8.6');
