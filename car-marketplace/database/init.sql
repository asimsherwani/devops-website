CREATE DATABASE IF NOT EXISTS cars_db;

USE cars_db;

CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    mileage INT,
    engine VARCHAR(100),
    transmission VARCHAR(50),
    fuel_type VARCHAR(50),
    color VARCHAR(50),
    city VARCHAR(100),
    registration VARCHAR(100),
    owner_name VARCHAR(150),
    owner_phone VARCHAR(50),
    description TEXT,
    image VARCHAR(500)
);

INSERT INTO cars (
    make,
    model,
    year,
    price,
    mileage,
    engine,
    transmission,
    fuel_type,
    color,
    city,
    registration,
    owner_name,
    owner_phone,
    description,
    image
)
VALUES

(
    'Toyota',
    'Corolla Altis',
    2024,
    7200000,
    12000,
    '1.6L',
    'Automatic',
    'Petrol',
    'White',
    'Lahore',
    'Lahore',
    'Ali Khan',
    '0300-1111111',
    'Excellent condition Toyota Corolla Altis with complete maintenance history.',
    'https://images.unsplash.com/photo-1623869675781-80aa31012a5a'
),

(
    'Honda',
    'Civic Oriel',
    2023,
    8400000,
    18000,
    '1.5L Turbo',
    'CVT',
    'Petrol',
    'Black',
    'Islamabad',
    'Islamabad',
    'Usman Ahmed',
    '0301-2222222',
    'Honda Civic Oriel in excellent condition with complete service history.',
    'https://images.unsplash.com/photo-1590362891991-f776e747a588'
),

(
    'Toyota',
    'Fortuner',
    2022,
    15500000,
    31000,
    '2.7L',
    'Automatic',
    'Petrol',
    'Black',
    'Lahore',
    'Lahore',
    'Ahmed Raza',
    '0321-3333333',
    'Premium Toyota Fortuner maintained in excellent condition.',
    'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b'
),

(
    'KIA',
    'Sportage',
    2023,
    8800000,
    21000,
    '2.0L',
    'Automatic',
    'Petrol',
    'White',
    'Karachi',
    'Karachi',
    'Bilal Hassan',
    '0333-4444444',
    'Family-owned Kia Sportage with excellent interior and drive.',
    'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2'
),

(
    'Suzuki',
    'Swift GLX',
    2024,
    4700000,
    8000,
    '1.2L',
    'CVT',
    'Petrol',
    'Silver',
    'Lahore',
    'Lahore',
    'Hamza Malik',
    '0305-5555555',
    'Almost new Suzuki Swift GLX with very low mileage.',
    'https://images.unsplash.com/photo-1503376780353-7e6692767b70'
),

(
    'Hyundai',
    'Tucson',
    2023,
    9400000,
    16000,
    '2.0L',
    'Automatic',
    'Petrol',
    'Grey',
    'Islamabad',
    'Islamabad',
    'Fahad Khan',
    '0345-6666666',
    'Hyundai Tucson with premium interior and complete maintenance record.',
    'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7'
);