<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/BookingRepository.php';

class ReservationController extends AppController {

    private $bookingRepository;

    public function __construct() {
        parent::__construct();
        $this->bookingRepository = new BookingRepository();
    }

    public function reservation() { 
        $this->requireLogin();

        if ($this->isPost()) {
            return $this->handleReservationPost();
        }

        return $this->handleReservationGet();
    }

    public function checkAvailability() {
        header('Content-Type: application/json');

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        
        if (!is_array($data)) {
            echo json_encode([]);
            exit();
        }

        $date = $data['date'] ?? null;
        $start = $data['start_time'] ?? null;
        $end = $data['end_time'] ?? null;
        $bookingId = $data['booking_id'] ?? null;

        if (!$date || !$start || !$end) {
            echo json_encode([]);
            exit();
        }

        $bookedRooms = $this->bookingRepository->getBookedRoomIds(
            $date, 
            $start, 
            $end, 
            $bookingId ? (int)$bookingId : null
        );
        
        echo json_encode($bookedRooms);
        exit();
    }

    private function handleReservationPost() {
        $date = $_POST['date'] ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? '';
        $roomId = trim($_POST['room_id'] ?? '');
        
        $bookingId = !empty($_POST['booking_id']) ? $_POST['booking_id'] : null;
        
        $ownerId = $_POST['booking_owner_id'] ?: $_SESSION['user_id'];

        if (empty($roomId)) {
            return $this->renderWithData('Please, choose the room from the map.', $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
        }

        try {
            $this->bookingRepository->beginTransaction();

            $this->validateBookingLogic($date, $startTime, $endTime, $roomId, $bookingId);

            if ($bookingId) {
                $this->bookingRepository->updateBooking((int)$bookingId, (int)$ownerId, $roomId, $date, $startTime, $endTime);
            } else {
                $this->bookingRepository->addBooking((int)$ownerId, $roomId, $date, $startTime, $endTime);
            }

            $this->bookingRepository->commit();

            $redirectUrl = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') 
                ? '/admin_bookings' 
                : '/mybookings';
            
            return $this->redirect($redirectUrl);

        } catch (Exception $e) {
            $this->bookingRepository->rollBack();
            return $this->renderWithData($e->getMessage(), $date, $startTime, $endTime, $roomId, $bookingId, $ownerId);
        }
    }

    private function validateBookingLogic($date, $startTime, $endTime, $roomId, $bookingId) {
        $bookingStart = new DateTime("$date $startTime");
        $bookingEnd = new DateTime("$date $endTime");
        $now = new DateTime();
        
        if ($bookingStart < $now) {
            throw new Exception('You cannot book in the past time.');
        }
        
        if ($bookingEnd <= $bookingStart) {
            throw new Exception('End time should be later than the start time.');
        }

        $interval = $bookingStart->diff($bookingEnd);
        if (($interval->h * 60 + $interval->i) < 15) {
             throw new Exception('Booking should last at least 15 minutes.');
        }
        
        $bookedRooms = $this->bookingRepository->getBookedRoomIds($date, $startTime, $endTime, (int)$bookingId);
        
        if (in_array($roomId, $bookedRooms)) {
            throw new Exception('This room is already booked for the selected time slot.');
        }
    }

    private function handleReservationGet() {
        $bookingId = !empty($_GET['booking_id']) ? (int)$_GET['booking_id'] : null;

        $existingBooking = $bookingId ? $this->bookingRepository->getBookingById($bookingId) : null;
        $bookingOwnerId = $existingBooking ? $existingBooking->getUserId() : null;

        $selectedRoomId = $_GET['room_id'] ?? ($existingBooking ? $existingBooking->getRoomId() : null);
        $selectedDate   = $_GET['date']    ?? ($existingBooking ? $existingBooking->getDate() : null);
        
        $selectedStart = $_GET['start'] ?? null;
        $selectedEnd   = $_GET['end']   ?? null;

        if (!$selectedStart && $existingBooking) {
            $times = explode(' - ', $existingBooking->getTimeRange());
            $selectedStart = $times[0] ?? '';
            $selectedEnd   = $times[1] ?? '';
        }

        return $this->render('reservation', [
            'booking_id'       => $bookingId,
            'booking_owner_id' => $bookingOwnerId,
            'selected_room_id' => $selectedRoomId,
            'selected_date'    => $selectedDate,
            'selected_start'   => $selectedStart,
            'selected_end'     => $selectedEnd
        ]);
    }

    private function renderWithData($message, $date, $start, $end, $roomId, $bookingId, $ownerId) {
        return $this->render('reservation', [
            'message'          => $message,
            'selected_date'    => $date,
            'selected_start'   => $start,
            'selected_end'     => $end,
            'selected_room_id' => $roomId,
            'booking_id'       => $bookingId,
            'booking_owner_id' => $ownerId
        ]);
    }
}