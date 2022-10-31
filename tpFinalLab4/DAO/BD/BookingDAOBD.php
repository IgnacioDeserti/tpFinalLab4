<?php
namespace DAO\BD;

use Models\Booking as Booking;
use DAO\BD\IBookingDAOBD as IBookingDAOBD;
use \Exception as Exception;
use DAO\BD\Connection as Connection;

class BookingDAOBD implements IBookingDAOBD
{
    private $connection;
    private $tableName = "booking";


    public function Add($booking)
    {
        try 
        {
            $pet=$booking->getPet();
            $pet_id=$booking->getPet()->getIdPet();

            $query="INSERT INTO ".$this->tableName." (bookingDate,startDate,endDate,petid,keeperid,fee, paidAmount,isConfirmed) VALUES (:bookingDate,:startDate,:endDate,:petid,:keeperid,:fee,:paidAmount, :isConfirmed);";
            $parameters["bookingDate"]=$booking->getBookingDate();
            $parameters["startDate"]=$booking->getStartDate();
            $parameters["endDate"]=$booking->getEndDate();
            $parameters["petid"]=$booking->getPet()->getIdPet();
            $parameters["keeperid"]=$booking->getKeeper()->getKeeperId();
            $parameters["fee"]=$booking->getFee();
            $parameters["paidAmount"]=$booking->getPaidAmount();
            $parameters["isConfirmed"]=$booking->getIsConfirmed();

            $this->connection=Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query,$parameters);

        } catch (Exception $ex) {
            throw $ex;
        }

    }

    public function GetAll()
    {
        try 
        {
            $query="SELECT * FROM ".$this->tableName;

            $this->connection=Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);

            $bookingList=array();
            foreach ($resultSet as $row)
            {
                $keeperList = new KeeperDAOBD();
                $petList = new PetDAOBD();
                $booking=new Booking();
                $booking->setBookingNumber($row["bookingNr"]);
                $booking->setBookingDate($row["bookingDate"]);
                $booking->setKeeper($keeperList->GetKeeperByKeeperId($row["keeperid"]));
                $booking->setPet($petList->GetPetByPetId($row["petid"]));
                $booking->setStartDate($row["startDate"]);
                $booking->setEndDate($row["endDate"]);
                $booking->setFee($row["fee"]);
                $booking->setPaidAmount($row["paidAmount"]);
                $booking->setIsConfirmed($row["isConfirmed"]);
                

                array_push($bookingList, $booking);
            }
            return $bookingList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function GetBookingBybookingNr($bookingNr)
    {
        $bookingList=$this->GetAll();
        $bookingFound = new Booking();
        foreach ($bookingList as $booking)
        {
            if($bookingNr == $booking->getBookingNumber())
            {
                $bookingFound=$booking;
            }
        }
        return $bookingFound;
    }

    public function ConfirmBooking($bookingNr)
    {
        try {
            $query="UPDATE ".$this->tableName." SET isConfirmed='Yes' WHERE bookingNr= :bookingNr;";
            $parameters["bookingNr"]=$bookingNr;

            $this->connection=Connection::GetInstance();
            $this->connection->ExecuteNonQuery($query,$parameters);

            
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
?>