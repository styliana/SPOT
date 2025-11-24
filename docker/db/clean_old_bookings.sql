-- Plik: docker/db/clean_old_bookings.sql
-- Procedura usuwa rezerwacje, których czas zakończenia jest starszy niż aktualna data i czas.
CREATE OR REPLACE PROCEDURE clean_archived_bookings()
LANGUAGE plpgsql
AS $$
BEGIN
    -- Używamy CURRENT_TIMESTAMP do porównania z pełnym czasem zakończenia rezerwacji
    -- Łączymy datę (date) i czas zakończenia (end_time) w jeden timestamp
    DELETE FROM bookings
    WHERE (date::text || ' ' || end_time::text)::timestamp < CURRENT_TIMESTAMP;

    -- Opcjonalny komunikat, jeśli procedura jest wywoływana ręcznie:
    -- RAISE NOTICE 'Usunięto archiwalne rezerwacje przed %', CURRENT_TIMESTAMP;
END;
$$;