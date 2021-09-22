<?php

class SLN_Helper_Availability_Highend_DayBookings extends SLN_Helper_Availability_AbstractDayBookings
{
    protected $ignoreServiceBreaks = false;
    /**
     * @return DateTime
     */
    public function getTime($hour = null, $minutes = null)
    {
        if (!isset($hour)) {
            $hour = $this->getDate()->format('H');
        }
        $now = clone $this->getDate();
        $now->setTime($hour, $minutes ? $minutes : 0);

        return $now;
    }

    protected function buildTimeslots()
    {
        $ret = array();
        $formattedDate = $this->getDate()->format('Y-m-d');
        
        foreach($this->minutesIntervals as $t) {
            $ret[$t] = array('booking' => array(), 'service' => array(), 'attendant' => array(),'holidays' => array());
            if($this->holidays){
                foreach ($this->holidays as $holiday){
                    $hData = $holiday->getData();
                    if( !$holiday->isValidTime($formattedDate.' '.$t)) $ret[$t]['holidays'][] = $hData;
                }
            }
        }

        $settings = SLN_Plugin::getInstance()->getSettings();
        $bookingOffsetEnabled = $settings->get('reservation_interval_enabled');
        $bookingOffset = $settings->get('minutes_between_reservation');

        /** @var SLN_Wrapper_Booking[] $bookings */
        $bookings = apply_filters('sln_build_timeslots_bookings_list', $this->bookings, $this->date, $this->currentBooking);
        foreach ($bookings as $booking) {
            $bookingServices = $booking->getBookingServices();
            foreach ($bookingServices->getItems() as $bookingService) {
                $times = SLN_Func::filterTimes(
                    $this->minutesIntervals,
                    $bookingService->getStartsAt(),
                    $bookingService->getEndsAt()
                );
                foreach ($times as $time) {
                    $key = $time->format('H:i');
                    if (($time < $bookingService->getBreakWithOffsetStartsAt() || $time >= $bookingService->getBreakWithOffsetEndsAt()) && apply_filters('sln_build_timeslots_add_booking_to_timeslot', true, $key, $booking, $this->bookings)) {
                        $ret[$key]['booking'][] = $booking->getId();
                    }
                    if ($time < $bookingService->getBreakStartsAt() || $time >= $bookingService->getBreakEndsAt()) {

			if ($bookingService->getService() && apply_filters('sln_build_timeslots_add_service_to_timeslot', true, $key, $bookingService, $booking, $this->bookings)) {
			    @$ret[$key]['service'][$bookingService->getService()->getId()]++;
			}

                        if ($bookingService->getService() && $bookingService->getAttendant() && apply_filters('sln_build_timeslots_add_attendant_to_timeslot', true, $key, $bookingService, $booking, $this->bookings)) {
                            @$ret[$key]['attendant'][$bookingService->getAttendant()->getId()]++;
                            @$ret[$key]['attendant_service'][$bookingService->getAttendant()->getId()][] = $bookingService->getService()->getId();
                        }
                    }
                }

                if ($bookingServices->isLast($bookingService) && $bookingOffsetEnabled) {
                    $offsetStart = $bookingService->getEndsAt();
                    $offsetEnd = clone  $bookingService->getEndsAt();
                    $offsetEnd = $offsetEnd->modify('+'.$bookingOffset.' minutes');
                    $times = SLN_Func::filterTimes($this->minutesIntervals, $offsetStart, $offsetEnd);
                    foreach ($times as $time) {
                        $time = $time->format('H:i');
			if (apply_filters('sln_build_timeslots_add_booking_to_timeslot', true, $time, $booking, $this->bookings)
			) {
			    $ret[$time]['disabled'][] = $booking->getId();
			}
                    }
                }
            }
        }

        return $ret;
    }
}
