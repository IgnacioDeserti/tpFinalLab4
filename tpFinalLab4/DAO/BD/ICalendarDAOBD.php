<?php

namespace DAO\BD;

use Models\Calendar as Calendar;
use Models\Keeper as CaleKeeperndar;

interface ICalendarDAOBD
{
    public function GetAll();
    public function Add(Calendar $calendar);
    public function GetAllByKeeper(Keeper $keeper)
}
?>