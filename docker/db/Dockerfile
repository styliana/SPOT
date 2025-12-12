FROM postgres:16-alpine

ENV POSTGRES_PASSWORD docker
ENV POSTGRES_DB db
ENV POSTGRES_USER docker

COPY . /app/database

COPY ./docker/db/structure.sql /docker-entrypoint-initdb.d/10-structure.sql
COPY ./docker/db/clean_old_bookings.sql /docker-entrypoint-initdb.d/20-clean_old_bookings.sql