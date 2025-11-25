-- 1. TWORZENIE STRUKTURY TABEL

-- Tabela ról (słownikowa)
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabela użytkowników
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_role INTEGER NOT NULL REFERENCES roles(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela pokoi
CREATE TABLE IF NOT EXISTS rooms (
    id VARCHAR(50) PRIMARY KEY, -- np. 'ROOM1'
    name VARCHAR(100) NOT NULL,
    workspaces INTEGER NOT NULL,
    type VARCHAR(50),
    description TEXT
);

-- Tabela rezerwacji
CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    room_id VARCHAR(50) NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'Confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela do logowania usuniętych rezerwacji (Dla WYZWALACZA)
CREATE TABLE IF NOT EXISTS bookings_audit_log (
    log_id SERIAL PRIMARY KEY,
    booking_id INTEGER,
    user_id INTEGER,
    room_id VARCHAR(50),
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT
);

-- Wstawienie podstawowych ról (Seed)
INSERT INTO roles (name) VALUES ('student'), ('teacher'), ('admin') ON CONFLICT DO NOTHING;


-- 2. WIDOKI (VIEWS) - Wymagane min. 2

-- Widok 1: Szczegóły rezerwacji (łączenie 3 tabel: bookings, users, rooms)
CREATE OR REPLACE VIEW vw_booking_details AS
SELECT 
    b.id AS booking_id,
    b.date,
    b.start_time,
    b.end_time,
    b.status,
    u.email AS user_email,
    u.name || ' ' || u.surname AS user_fullname,
    r.name AS room_name,
    r.type AS room_type
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN rooms r ON b.room_id = r.id;

-- Widok 2: Statystyki pokoi (grupowanie i liczenie)
CREATE OR REPLACE VIEW vw_room_stats AS
SELECT 
    r.name AS room_name,
    COUNT(b.id) AS total_bookings,
    MAX(b.date) AS last_booking_date
FROM rooms r
LEFT JOIN bookings b ON r.id = b.room_id
GROUP BY r.name;


-- 3. WYZWALACZ (TRIGGER) - Wymagane min. 1

-- Funkcja wyzwalacza
CREATE OR REPLACE FUNCTION log_booking_deletion()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO bookings_audit_log (booking_id, user_id, room_id, reason)
    VALUES (OLD.id, OLD.user_id, OLD.room_id, 'Booking deleted/cancelled');
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

-- Podpięcie wyzwalacza do tabeli bookings
DROP TRIGGER IF EXISTS trg_log_booking_delete ON bookings;
CREATE TRIGGER trg_log_booking_delete
AFTER DELETE ON bookings
FOR EACH ROW
EXECUTE FUNCTION log_booking_deletion();