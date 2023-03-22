<?php

namespace Simcify\Models;

use Simcify\Database;

class RoomModel
{
    const Vacant = 'Vacant';
    const Occupied = 'Occupied';
    const Unavailable = 'Unavailable';

    public static function SetVacant($bed_id)
    {
        if ($bed_id > 0) {  //previous bed should be Vacant status.
            Database::table("beds")->where("id", $bed_id)->update(array('student_id' => 0, 'status' => RoomModel::Vacant));
        }
    }
}
