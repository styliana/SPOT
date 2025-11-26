© Aleksandra Gołek | All rights reserved.
This work is legally protected and requires permission for use.
## What is SPOT? ✨

> Smart Place Organization Tool | "Your campus. Your space. Your SPOT."

**Say goodbye to booking conflicts, uncertainty, and the hassle of manually searching for available rooms. SPOT (Smart Place Organization Tool) is a revolutionary platform for academic space management, designed for the prestige and dynamism of the modern university.**

SPOT combines an elegant interface with a powerful organizational engine that automates the booking process for rooms, labs, and lecture halls. It's a tool that brings order and peace of mind, allowing the entire academic community to focus on what matters most: learning, development, and collaboration.
## 🚀 Start-up

1.  Make sure you have Docker installed.
2.  Clone the repo and go to project directory.
3.  Launch the app with:
    ```bash
    docker-compose up --build
    ```
4.  **App:** `http://localhost:8080`
5.  **PgAdmin:** `http://localhost:5050` (Login: `admin@example.com`, Hasło: `admin`)

**Default login:**
* **Student:** `student@spot.com` 
* **Admin:** `admin@example.com`

## 🏗 Architecture & Structure

App is based on **MVC (Model-View-Controller)**. All traffic is managed by `index.php` (Front Controller) and class `Routing`.

**Layer diagram:**
```mermaid
graph TD
    User((Użytkownik)) --> Browser[Przeglądarka / JS Fetch]
    Browser -- HTTP Request --> Nginx[Nginx :8080]
    Nginx -- FastCGI --> PHP[Kontener PHP :9000]
    
    subgraph Backend PHP
    PHP --> Router[Routing.php]
    Router --> Controller[Controller]
    Controller --> Model[Model]
    Controller --> View[View / Template]
    Model --> Repo[Repository]
    end
    
    Repo -- PDO --> DB[(PostgreSQL :5432)]
```

## Database
### Entity Relationship Diagram (ERD)
## 🗄 Database

### Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    ROLES {
        int id PK "Unique identifier"
        string name "Role name (student, admin)"
    }

    USERS {
        int id PK "Unique identifier"
        string email UK "Login email"
        string password "Hashed password"
        string name "First name"
        string surname "Last name"
        int id_role FK "Foreign key to ROLES"
        timestamp created_at "Creation date"
    }

    USER_DETAILS {
        int user_id PK,FK "Links to USERS"
        string phone_number "Contact number"
        string faculty "Faculty name"
        string student_card_id "ID Card number"
    }

    ROOMS {
        string id PK "Room code (e.g. ROOM1)"
        string name "Display name"
        int workspaces "Seat capacity"
        string type "Type (Lab, Lecture Hall)"
        text description "Optional details"
    }

    BOOKINGS {
        int id PK "Booking ID"
        int user_id FK "Who booked"
        string room_id FK "Which room"
        date date "Reservation date"
        time start_time "Start"
        time end_time "End"
        string status "Confirmed/Cancelled"
    }

    BOOKINGS_AUDIT_LOG {
        int log_id PK "Log ID"
        int booking_id "Deleted booking ID"
        string reason "Reason for deletion"
        timestamp deleted_at "Time of deletion"
    }

    %% Relacje
    ROLES ||--|{ USERS : "defines permissions for"
    USERS ||--|| USER_DETAILS : "has details"
    USERS ||--o{ BOOKINGS : "makes"
    ROOMS ||--o{ BOOKINGS : "is reserved in"
```

### Relationships
.........

## Advanced SQL elements
* Views: vw_booking_details (details with table joining), vw_room_stats (usage stats).
* Trigger: trg_log_booking_delete - automatically logs deleted bookings to the table bookings_audit_log.
* Procedure: clean_archived_bookings - deletes old bookings (from the past).

<h3>PROGRAMMED VIEWS</h3>

<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
  <img src="https://github.com/user-attachments/assets/fb7fd3e3-1e20-4702-af2e-a2aec069b6da" width="48%">
  <img src="https://github.com/user-attachments/assets/02af901b-3a9f-4e0e-9b31-c95d6b8cb397" width="48%">
  <img src="https://github.com/user-attachments/assets/dcc9d344-c549-46a3-8a52-79b0f4923e67" width="48%">
  <img src="https://github.com/user-attachments/assets/ac349bee-2d4c-4433-996e-db6de14baaf9" width="48%">
  <img src="https://github.com/user-attachments/assets/14fc8044-6c9e-4800-940a-deef6337c731" width="48%">
  <img src="https://github.com/user-attachments/assets/7a3a96f8-fba7-4329-bb19-9196a7c9d360" width="48%">
  <img src="https://github.com/user-attachments/assets/47086787-e6f8-4a01-b7ea-8796233125cf" width="48%">
  <img src="https://github.com/user-attachments/assets/caa1f548-f0c8-4226-8475-9c2cdbe75fd5" width="48%">
  <img src="https://github.com/user-attachments/assets/68e40379-d48e-443b-b17b-e1cc74c94dbf" width="48%">
  <img src="https://github.com/user-attachments/assets/519532f5-e729-4056-9903-b6cfda220929" width="48%">
</div>

<h3>ERD DIAGRAM and STRUCTURE</h3>
SOON...........

<h3>PROTOTYPES</h3>

Desktop versions

<img width="736" height="817" alt="image" src="https://github.com/user-attachments/assets/0585f691-3963-410f-adc5-468d78ed6f50" />

Mobile

<img width="605" height="794" alt="image" src="https://github.com/user-attachments/assets/30dec495-8e0a-4acc-9c52-670f2886015e" />



