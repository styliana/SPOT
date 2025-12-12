<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/BookingRepository.php';

class BookingsController extends AppController {

    private $bookingRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bookingRepository = new BookingRepository();
    }

    public function mybookings() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $bookingsObjects = $this->bookingRepository->getBookingsByUserId($userId);
        $bookingsData = [];
        
        foreach ($bookingsObjects as $booking) {
            $statusPill = 'pill-gray';
            if ($booking->getStatus() === 'Confirmed') {
                $statusPill = 'pill-green';
            } elseif ($booking->getStatus() === 'Cancelled') {
                $statusPill = 'pill-red';
            } elseif ($booking->getStatus() === 'Pending') {
                $statusPill = 'pill-orange';
            }

            $bookingsData[] = [
                'id' => $booking->getId(),
                'room_id' => $booking->getRoomId(), 
                'room_name' => $booking->getRoomName(),
                'date' => $booking->getDate(),
                'time' => $booking->getTimeRange(),
                'type' => $booking->getRoomType(),
                'type_pill' => 'pill-blue', 
                'status' => $booking->getStatus(),
                'status_pill' => $statusPill
            ];
        }

        return $this->render('mybookings', ['bookings' => $bookingsData]);
    }

    public function delete() {
        $this->requireLogin();

        if (!$this->isPost()) {
            return $this->redirect('/mybookings');
        }

        $bookingId = $_POST['id'];
        $this->bookingRepository->deleteBooking($bookingId);

        return $this->redirect('/mybookings');
    }
}