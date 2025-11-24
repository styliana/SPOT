# Plik: docker/db/Dockerfile

FROM postgres

ENV POSTGRES_PASSWORD docker
ENV POSTGRES_DB db
ENV POSTGRES_USER docker

COPY . /app/database

# DODANIE NOWEGO PLIKU SQL
COPY ./docker/db/clean_old_bookings.sql /docker-entrypoint-initdb.d/