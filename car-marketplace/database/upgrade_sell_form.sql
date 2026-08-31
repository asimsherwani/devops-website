USE cars_db;

ALTER TABLE cars
    ADD COLUMN owner_email VARCHAR(150) NULL AFTER owner_name,
    ADD COLUMN remarks TEXT NULL AFTER description,
    ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'approved',
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE IF NOT EXISTS car_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_car_images_car
        FOREIGN KEY (car_id)
        REFERENCES cars(id)
        ON DELETE CASCADE
);