© Aleksandra Gołek | All rights reserved.
This work is legally protected and requires permission for use.
## What is SPOT? ✨

> Smart Place Organization Tool | "Your campus. Your space. Your SPOT."

**Say goodbye to booking conflicts, uncertainty, and the hassle of manually searching for available rooms. SPOT (Smart Place Organization Tool) is a revolutionary platform for academic space management, designed for the prestige and dynamism of the modern university.**

SPOT combines an elegant interface with a powerful organizational engine that automates the booking process for rooms, labs, and lecture halls. It's a tool that brings order and peace of mind, allowing the entire academic community to focus on what matters most: learning, development, and collaboration.

Read the documentation:
## 📋 Table of Contents
0. Technologies
1. Start-up
2. Architecture & Structure
3. Database (ERD & advanced SQL elements)
4. Main functionalities
5. Manual test scenario
6. Quality by Lighthouse
7. Requirements Checklist

## 💻 0. Technologies
The project is implemented in accordance with the strict requirements (no PHP frameworks):
* **Backend:** PHP 8.3 (Object-Oriented, PDO, MVC Pattern)
* **Frontend:** HTML5, CSS3 (Responsive/Flexbox/Grid), JavaScript (Vanilla + Fetch API)
* **Database:** PostgreSQL 16
* **Containerization:** Docker & Docker Compose
* **Web Server:** Nginx (Alpine)

## 🚀 1. Start-up

1.  Make sure you have Docker installed.
2.  Clone the repo and go to project directory.
3.  Launch the app with:
    ```bash
    docker-compose up --build
    ```
4.  **App:** `http://localhost:8080`
5.  **PgAdmin:** `http://localhost:5050` (Login: `admin@example.com`, Hasło: `admin`)

**Default login examples (u can make users like that):**
* **Student:** `student@spot.com` 
* **Admin:** `admin@example.com`

## 🏗 2. Architecture & Structure

App is based on **MVC (Model-View-Controller)**. All traffic is managed by `index.php` (Front Controller) and class `Routing`.

**Layer diagram:**
```mermaid
graph TD
    User((Użytkownik)) --> Browser[Przeglądarka / JS Fetch]
    Browser -- HTTP Request --> Nginx[Nginx :8080]
    Nginx -- FastCGI --> PHP[Kontener PHP]
    
    subgraph Backend PHP
    PHP --> Router[Routing.php]
    Router --> Controller[Controller]
    Controller --> Model[Model]
    Controller --> View[View / Template]
    Model --> Repo[Repository]
    end
    
    Repo -- PDO --> DB[(PostgreSQL :5432)]
```


## 🗄 3 Database
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

    USER_CREATION_LOGS {
        int user_id PK,FK "User ID"
        timestamp created_at_log "Log timestamp"
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
        timestamp created_at "Booking timestamp"
    }

    BOOKINGS_AUDIT_LOG {
        int log_id PK "Log ID"
        int booking_id "Deleted booking ID"
        int user_id "User ID from booking"
        string room_id "Room ID from booking"
        string reason "Reason for deletion"
        timestamp deleted_at "Time of deletion"
    }

    %% Relacje
    ROLES ||--|{ USERS : "defines permissions for"
    USERS ||--|| USER_CREATION_LOGS : "logs creation in"
    USERS ||--o{ BOOKINGS : "makes"
    ROOMS ||--o{ BOOKINGS : "is reserved in"
```

<img width="903" height="885" alt="image" src="https://github.com/user-attachments/assets/a53b5a45-1c1e-4822-b5a3-c355610e6514" />


## Advanced SQL elements
Views: 
- vw_booking_details: Aggregates booking data with user and room details using JOINs.
- vw_room_stats: Calculates usage statistics for each room.
  
Trigger:
- trg_log_booking_delete: Automatically saves information about deleted bookings into the bookings_audit_log table for security/audit purposes.
  
Procedure:
- clean_archived_bookings: A stored procedure that cleans up historical reservation data to maintain database performance.

Stored function:
- log_booking_deletion(): Contains the logic executed by the trigger. Captures the details of a deleted booking (ID, user, room) and inserts a record into the bookings_audit_log table.

Transaction: To ensure data integrity. Multiple SQL operations are treated as a single atomic unit—either all succeed, or all fail (rollback).
- User Registration: When adding new user, system inserts it into the users table and creates a log entry in user_creation_logs in 1 transaction.
- Reservations: When creating or updating a booking, the system validates the logic and saves the booking data inside a transaction to prevent race conditions or partial updates.




## 🌟 4. Main functionalities
- Logging and sesions: Secure authentication with password hashing.
- Registration: live input validation.
- Roles: Role system (Student/Teacher/Admin) with access blocking (Middleware checkAdmin).
- Reservations: Interactive SVG map, date&time validation, bookings list, database conflict resolving.
- Admin panel: User, rooms, bookings management (CRUD).
- User profile: User data edition.


## 🧪 5. Test Scenario

<table>
  <tr>
    <td width="50%" valign="top">
      <strong>1. Log in.</strong><br>
      <img src="https://github.com/user-attachments/assets/69ba7c31-ff53-492e-ae40-43e6b46f10a5" width="100%" />
    </td>
    <td width="50%" valign="top">
      <strong>2. See current bookings.</strong><br>
      <img src="https://github.com/user-attachments/assets/0a8fb53a-f2e6-4072-9a6a-eda483b3c28a" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>3. Adding reservation.</strong><br>
      <img src="https://github.com/user-attachments/assets/6c622866-5835-4a4f-884d-b50670ba0f1a" width="100%" />
    </td>
    <td valign="top">
      <strong>4. Trying to add reservation with no chosen room.</strong><br>
      <img src="https://github.com/user-attachments/assets/19caaec0-f1d4-4322-b4cf-443f38e53c95" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>5. Choosing the room from the map and details display.</strong><br>
      <img src="https://github.com/user-attachments/assets/a2350e45-e0c0-4665-8baf-190af1217164" width="100%" />
    </td>
    <td valign="top">
      <strong>6. Valid date, time and room. Proceeding to make a booking.</strong><br>
      <img src="https://github.com/user-attachments/assets/53d73232-120f-44db-a26b-0a12221b3b8b" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>7. New reservation visibe on the list. Old bookings are deleted automatically.</strong><br>
      <img src="https://github.com/user-attachments/assets/0a2348a8-6872-4439-be17-1df539b94f1e" width="100%" />
    </td>
    <td valign="top">
      <strong>8. Editing any chosen reservation (date, time, room).</strong><br>
      <img src="https://github.com/user-attachments/assets/802bf6c3-ff56-4e74-aa82-b460da22d940" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>9. Choosing the time from the past. Message displayed.</strong><br>
      <img src="https://github.com/user-attachments/assets/92a5abe5-943d-41fd-a2b4-855b52fba40e" width="100%" />
    </td>
    <td valign="top">
      <strong>10. Dipslay user profile.</strong><br>
      <img src="https://github.com/user-attachments/assets/287e36c2-e909-4a9e-8fb2-d5e69ef481f5" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>11. Edit user profile (name, lastname, password).</strong><br>
      <img src="https://github.com/user-attachments/assets/eede2458-5ebd-4d59-bc1e-45791d9d5ac2" width="100%" />
    </td>
    <td valign="top">
      <strong>12. Profile data updated.</strong><br>
      <img src="https://github.com/user-attachments/assets/57c73884-4520-44d6-a097-163764240a23" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>13. Log out. Log in as admin.</strong><br>
      <img src="https://github.com/user-attachments/assets/0176eddc-a095-40a9-a68d-386b58fcb475" width="100%" />
    </td>
    <td valign="top">
      <strong>14. Display users and change their role.</strong><br>
      <img src="https://github.com/user-attachments/assets/f0ad1abe-fba6-45cd-af16-a7dca5d1054a" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>15. Edit user.</strong><br>
      <img src="https://github.com/user-attachments/assets/71c5ee45-b48a-4979-814f-40410cd17126" width="100%" />
    </td>
    <td valign="top">
      <strong>16. Display rooms and add new room.</strong><br>
      <img src="https://github.com/user-attachments/assets/11ef0850-083d-4fcd-a28c-dcf68f048827" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>17. Manage user bookings.</strong><br>
      <img src="https://github.com/user-attachments/assets/53f186e9-343c-437a-874a-8e073e3927f4" width="100%" />
    </td>
    <td valign="top">
      <strong>18. Search by the keywords.</strong><br>
      <img src="https://github.com/user-attachments/assets/b8f84281-47da-432f-b589-1e87529e23fa" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>19. Non existing page (404).</strong><br>
      <img src="https://github.com/user-attachments/assets/7a32064f-6a2f-4812-a892-56dfa5d7fbc3" width="100%" />
    </td>
    <td valign="top">
      <strong>20. Triggered (from the code level) exception (500).</strong><br>
      <img src="https://github.com/user-attachments/assets/0e2a4700-f742-472e-9de9-1488a0c158ec" width="100%" />
    </td>
  </tr>
  <tr>
    <td valign="top">
      <strong>21. Trying to access admin page being logged in as a student/teacher.</strong><br>
      <img src="https://github.com/user-attachments/assets/70075961-a2f8-4d4c-8250-e412e141dd71" width="100%" />
    </td>
    <td valign="top">
        <strong>22. Admin adding a room that ID already exists in db - message displayed.</strong><br>
        <img width="1842" height="875" alt="image" src="https://github.com/user-attachments/assets/d36fdbad-638e-44d4-82ea-27751a9eca01" />

      </td>
  </tr>
</table>

## 📱 Mobile Views

<table> <tr> <td width="25%" valign="top"> <strong>Log in page.</strong>


<img src="https://github.com/user-attachments/assets/c594f10a-071f-4468-b14a-0f804194f63f" width="100%" /> </td> <td width="25%" valign="top"> <strong>Register account. Type non identical passwords.</strong>


<img src="https://github.com/user-attachments/assets/06bd065f-b937-456e-a1ab-7c2eef058f8d" width="100%" /> </td> <td width="25%" valign="top"> <strong>Successful login.</strong>


<img src="https://github.com/user-attachments/assets/d1a10269-c68d-4798-8384-d1f1d61e67b2" width="100%" /> </td> <td width="25%" valign="top"> <strong>Add reservation.</strong>


<img src="https://github.com/user-attachments/assets/e3684baa-ac68-4e14-8860-d53139d6cfa8" width="100%" /> </td> </tr> <tr> <td valign="top"> <strong>Chosen room.</strong>


<img src="https://github.com/user-attachments/assets/1157a4c9-ee11-4edb-8653-536be4d3806e" width="100%" /> </td> <td valign="top"> <strong>Booking is chosen to be less than 15 minutes long. Message displayed.</strong>


<img src="https://github.com/user-attachments/assets/fdac4918-6ed0-41cb-9edd-c2decf0525b2" width="100%" /> </td> <td valign="top"> <strong>Display info about SPOT!</strong>


<img src="https://github.com/user-attachments/assets/0ad12fc4-e9f4-426b-986a-9ef27a175c73" width="100%" /> </td> <td valign="top"> <strong>Admin view: manage users.</strong>


<img src="https://github.com/user-attachments/assets/b0d896b2-3a6d-44ec-ad9f-1c3b1a049413" width="100%" /> </td> </tr> <tr> <td valign="top"> <strong>Admin view: manage users' bookings. Delete a reservation.</strong>




<img src="https://github.com/user-attachments/assets/a6de9ded-3e0f-43c2-9920-13aba38be565" width="100%" /> </td> <td valign="top"> <strong>Log out. Successfully redirected to log in page and user logged out.</strong>


<img src="https://github.com/user-attachments/assets/1f94ff1e-93f1-44c2-b31b-411e5f475759" width="100%" /> </td> </tr> </table>

## 6. Quality rated by Lighthouse: AMAZING!

<table>
  <tr>
    <td width="50%" valign="top">
      <strong>My bookings page.</strong><br>
      <img width="1915" height="970" alt="image" src="https://github.com/user-attachments/assets/9e600c6c-da84-4649-88b0-65da4bea2ea4" />
    </td>
    <td width="50%" valign="top">
      <strong>Reservation page.</strong><br>
      <img width="1910" height="914" alt="image" src="https://github.com/user-attachments/assets/5bc22a85-9207-4918-9f57-5bdfe054a888" />
    </td>
  </tr>
</table>

## ✅ 7. Requirements Checklist
- Technologies: Docker, GIT, HTML5, CSS, JS, PHP, PostgreSQL.
- MVC Architecture without frameworks.
- Responsive Design (Media Queries).
- Login, Sessions, Permissions.
- Database: Relationships, 3rd Normal Form.
- Views (2), Trigger (1), Procedure (1).
- SQL Transactions (during reservation).
- Fetch API (room availability check).
- Tests (PHPUnit + Bash).
- Error Handling (400, 403, 404, 500 pages).
- Documentation and README.

## 8. Areas of improvement (future)
1) Filtering and sorting options for the user tables (as there is for admin).
2) Dynamic map change.
3) More specific limits (e.g. booking should not only last over 15 minutes but less than...) for booking.
4) Interactive messages after having completed reservation.
5) Booking assigned to teacher having more priority than student one.
6) Confirm reservation button 15 minutes before the reservation.
7) Room booking by: capacity, purpose, equipement. Not limiting by date and time.
