<?php namespace Controllers;

use Models\Calendar as Calendar;
//use DAO\CalendarDAO AS CalendarDAO;
use DAO\BD\CalendarDAOBD as CalendarDAOBD;
use DAO\BD\KeeperDAOBD;
use DateTime as DateTime;
use DateInterval as DateInterval;
use DatePeriod as DatePeriod;
use \Exception as Exception;

class CalendarController{
    
    private $calendarDAO;

    public function __construct(){
        $this->calendarDAO = new CalendarDAOBD();
    }

    public function ShowAddView(){
        require_once(VIEWS_PATH."addCalendarPeriod.php");
    }

    public function ShowListView(){
        $calendarList = $this->calendarDAO->GetAll();
        require_once(VIEWS_PATH."listCalendarPeriod.php");
    }

    public function ShowListViewByKeeper(){
        $keeper= $_SESSION["loggedKeeper"];
        $calendarList = $this->calendarDAO->GetAllByKeeper($keeper);
        require_once(VIEWS_PATH."listCalendarPeriod.php");
    }

    public function Add( $dateFrom, $dateTo){

        $keeper=$_SESSION['loggedKeeper']; 

        $interval = new DateInterval('P1D');  // Variable that store the date interval of period 1 day
        $end = new DateTime($dateTo);
        $end->add($interval);
  
        $period = new DatePeriod(new DateTime($dateFrom), $interval, $end); //Creation of the period
  
        // 
        foreach($period as $date) {                                         //Add EACH day as CalendarItem to CalendarDAO

            $calendarItem= new Calendar();

            $calendarItem->setKeeper($keeper); 
            $calendarItem->setDate($date->format('Y-m-d'));
            $calendarItem->setStatus("Available");

            $this->calendarDAO->Add($calendarItem);
        }

        $this->ShowListViewByKeeper();                                              //To show the recently added items
    }

    public function Remove($id)
    {
        $this->calendarDAO->Remove($id);
 
        $this->ShowListView();
    }

    public function ShowAvailableKeepersSearchView()
    {
        require_once(VIEWS_PATH."searchAvailableKeepers.php");
    }

    public function ShowAvailableKeepers($dateFrom, $dateTo)
    {
        $keeperList = $this->SearchAvailableKeepers($dateFrom, $dateTo);
        require_once(VIEWS_PATH."listKeeper.php");
    }

    public function SearchAvailableKeepers($dateFrom, $dateTo)
    {
        $requiredInterval = new DateInterval('P1D'); 
        $end = new DateTime($dateTo);
        $end->add($requiredInterval);

        $requiredPeriod = new DatePeriod(new DateTime($dateFrom), $requiredInterval, $end);

        $keepersList = new KeeperDAOBD();
        $keepersList = $keepersList->getAll(); //hace falta hacer el getall?
        $availableKeepersList = array();

        foreach ($keepersList as $keeper) {
            $calendarByKeeperList = $this->calendarDAO->CalendarByKeeper($keeper); //Brings all available days per keeper

            $available = true;
            foreach ($requiredPeriod as $day) {
                if (!in_array($day, $calendarByKeeperList)) //if at least 1 day it's not available, then turn unavailable.
                {
                    $available = false;
                }
            }
            if ($available = true) //if it's still available after checking all dates, push it into the array
            {
                array_push($availableKeepersList, $keeper);
            }
        }
        return $availableKeepersList;                 
    }        
}
?>