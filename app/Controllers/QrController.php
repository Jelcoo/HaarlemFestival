<?php

namespace App\Controllers;

use App\Helpers\QrCodeGenerator;
use App\Repositories\EventRepository;
use App\Repositories\TicketRepository;

class QrController extends Controller
{
    private EventRepository $eventRepository;
    private QrCodeGenerator $qrCodeGenerator;
    private TicketRepository $ticketRepository;
    public function __construct()
    {
        parent::__construct();
        $this->eventRepository = new EventRepository(); 
        $this->qrCodeGenerator = new QrCodeGenerator(); 
        $this->ticketRepository = new TicketRepository(); 
    }

    public function index(): string
    {
        $allDanceEvents = $this->eventRepository->getAllDanceInformation();
        $allYummyEvents = $this->eventRepository->getAllYummyEventInformation();
        $allHistoryEvents = $this->eventRepository->getAllHistoryEventInformation();
        //$qr = $this->qrCodeGenerator->generateQRCode(1,'history');
        return $this->pageLoader->setPage('qrcode')->render(['allHistoryEvents' => $allHistoryEvents, 'allYummyEvents' => $allYummyEvents, 'allDanceEvents' => $allDanceEvents]);
        //return $this->pageLoader->setPage('qrcode')->render(['allHistoryEvents' => $allHistoryEvents, 'allYummyEvents' => $allYummyEvents, 'allDanceEvents' => $allDanceEvents, 'qr' => $qr]);
    }
    public function scannerqrcode(){
        $qr = $_GET['qr'] ?? null;
        $ticketDetails = self::getTicketDetails($qr);
        $ticketId = $ticketDetails['id'];
        $ticketEvent = $ticketDetails['event'];

        $eventId = 0;
        $eventType = 'error';
        $parts = explode('|', $qr);
        if (count($parts) >= 3) {
            $eventId = (int)$parts[2];
            $eventType = $parts[1]; 
        } 
        $used = self::checkIfIsUsed($eventId, $ticketId, $eventType, $ticketEvent);
        return $this->pageLoader->setPage('scannedqrcode')->render(['ticketId' => $ticketId, 'used' => $used, 'eventType' => $eventType, 'ticketEvent' => $ticketEvent, 'eventId' => $eventId]);
    }
    public function useTicket()
    {
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr, true);

        $eventType = $jsonObj['eventType'] ?? null;
        $ticketId = $jsonObj['ticketId'] ?? null;
        $eventId = $jsonObj['eventId'] ?? null;
        $check = false;
        if($eventType == 'yummy'){
            $check = $this->ticketRepository->useRestaurantTicket($ticketId);
        }
        else if($eventType == 'history'){
            $check = $this->ticketRepository->useHistoryTicket($ticketId);
        }
        else if($eventType == 'dance'){
            $check = $this->ticketRepository->useDanceTicket($ticketId,$eventId);
        }
        else{
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        if(!$check){
            http_response_code(400);
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        echo json_encode(["success" => true, "message" => "Ticket used successfully"]);
        exit;
    }
    
    
    private function getTicketDetails($qr): array
    {
        if (!$qr) {
            return [
                'id' => 100,
                'event' => 'unknown'
            ];
        }
        $parts = explode('|', $qr); 
        if (count($parts) < 3) {
            return [
                'id' => 1000,
                'event' => 'unknown'
            ];
        }
        $encryptedData = $parts[0];
        try {
            $ticketDetails = QrCodeGenerator::readQrString($encryptedData); 
            return [
                'id' => (int)$ticketDetails['id'],
                'event' => $ticketDetails['event']
            ];
        } catch (\Exception $e) {
            return [
                'id' => 1000000,
                'event' => 'invalid'
            ];
        }
    }
    
    private function checkIfIsUsed($eventId, $ticketId, $eventType, $ticketEvent):bool{
        if($ticketEvent != $eventType){
            return true;
        }
        else if($eventType == 'dance'){
            return self::checkDanceUsed($eventId, $ticketId);
        }
        else if($eventType == 'history'){
            return !$this->ticketRepository->checkHistoryTicketFromEvent($eventId, $ticketId);
        }
        else if($eventType == 'yummy'){
            return !$this->ticketRepository->checkYummyTicketFromEvent($eventId, $ticketId);
        }
        else{
            return true;
        }
    }
    private function checkDanceUsed($eventId, $ticketId):bool{
        if($this->ticketRepository->checkifAllAcces($ticketId)){
            return $this->ticketRepository->checkValidityAllAcces($eventId, $ticketId);
        }
        return $this->ticketRepository->checkDanceTicketFromEvent($eventId, $ticketId);
    }
}
