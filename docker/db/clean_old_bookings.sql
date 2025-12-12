CREATE OR REPLACE PROCEDURE clean_archived_bookings()
LANGUAGE plpgsql
AS $$
BEGIN
    DELETE FROM bookings
    WHERE (date::text || ' ' || end_time::text)::timestamp < CURRENT_TIMESTAMP;
END;
$$;