<?php

namespace Simcify\Controllers;

use Simcify\Models\RoomModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;

class Room
{
    public function getList($page = '')
    {
        $rooms = array();
        $rooms_temp = array();
        if (isset($_REQUEST["builing"]))
            $rooms_temp = Database::table("rooms")->where("building_id", $_REQUEST["builing"])->get();
        else
            $rooms_temp = Database::table("rooms")->get();
        foreach ($rooms_temp as $each_room) {
            $each_room_temp = array();
            $each_room_temp = $each_room;
            $each_room_temp->host_data = Room::getHostData($each_room->id);

            $rooms[] = $each_room_temp;
        }

        $buildings = Database::table("buildings")->get();

        $user = Auth::user();
        return view('room' . $page, compact("user", "rooms", "buildings"));
    }

    public function getRoomList()
    {
        return $this->getList('/room_list');
    }

    public function create()
    {
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "description" => $_POST["description"],
            "building_id" => $_POST["building_id"],
            "member_id" => 0
        );

        Database::table("rooms")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Room", "Create Room", "Created Room : " . $_POST["name"]);

        exit(json_encode(responder("success", "Alright", "Room successfully created", "reload()")));
    }

    public function findHosts()
    {     
        $dirs = array();
        $added_host = array();
        $temp_dirs = array_filter(glob('uploads/hosts/*'), 'is_dir');
        $temp_hosts = Database::table("hosts")->get();
        foreach ($temp_dirs as $each_temp_dirs) {
            $dirs[] = str_replace("uploads/hosts/", "", $each_temp_dirs);
        }

        foreach ($temp_hosts as $each_temp_host) {
            $added_host[] = $each_temp_host->name;
        }

        $dirs = array_diff($dirs, $added_host);

        $user = Auth::user();
        return view('room/find_host', compact("user", "dirs"));
    }

    public function reviewHost($host_code){
        $user = Auth::user();
        $host = Database::table("hosts")->where("host_code", $host_code)->first();

        return view('room/review_host', compact("user", "host"));
    }

    public function getLastestHostFileAjax(){
        $file_path = 'uploads/hosts/'.input("host_name");
        $latest_file = $this->file_sort($file_path, "screen");
        $newest_file = url("/")."uploads/hosts/" . input("host_name") . "/" . $latest_file[0];
        echo $newest_file;
    }

    function file_sort($file_path, $compare_prefix){
        $files = scandir($file_path, SCANDIR_SORT_DESCENDING);
        $return_files = array();
        foreach($files as $each_file){
            $check_one_digit1 = explode(".", $each_file);
            $check_one_digit2 = explode($compare_prefix, $check_one_digit1[0]);
            if(strlen($check_one_digit2[1]) == 1){
                $return_files[] = $compare_prefix . "0" . $check_one_digit2[1] . "." .$check_one_digit1[1];
            }
            else{
                $return_files[] = $each_file;
            }
        }
        rsort($return_files);
        return $return_files;
    }

    public function createHost()
    {
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "description" => $_POST["description"],
            "room_id" => $_POST["room_id"],
            "host_code" => $this->generateRandomString(20)
        );

        Database::table("hosts")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Hosts", "Create Host", "Created Host : " . $_POST["name"]);

        exit(json_encode(responder("success", "Alright", "Host successfully created", "reload()")));
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function reviewRoom($room_id)
    {
        $user = Auth::user();
        $room = Database::table("rooms")->where("id", $room_id)->first();
        $building = Database::table("buildings")->where("id", $room->building_id)->first();
        $hosts = $this->getHostData($room_id);

        return view('room/review', compact("user", "room", "building", "hosts"));
    }

    public function delete()
    {
        $room = Database::table("rooms")->find(input("roomid"));

        Database::table("rooms")->where("id", input("roomid"))->delete();
        Database::table("hosts")->where("room_id", input("roomid"))->delete();


        // Action Log
        Customer::addActionLog("Room", "Delete Room", "Deleted Room : " . $room->name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Room Deleted!", "Student successfully deleted.", "reload()")));
    }

    public function ChangeHostStatus()
    {
        $bed = Database::table("beds")->find(input("bedid"));
        $data = array('status' => RoomModel::Vacant);
        if ($bed->status == RoomModel::Vacant)
            $data = array('status' => RoomModel::Unavailable);
        elseif ($bed->status == RoomModel::Occupied)
            RoomModel::SetVacant($bed->id);

        Database::table("beds")->where("id", $bed->id)->update($data);
        // Action Log
        Customer::addActionLog("Bed", "Change Status", $bed->name . " " . $data['status']);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Bed Status", "This Bed status was changed to " . $data['status'], "reload()")));
    }

    public function deleteHost()
    {
        $host = Database::table("hosts")->find(input("hostid"));
        Database::table("hosts")->where("id", input("hostid"))->delete();
        // Action Log
        Customer::addActionLog("Host", "Delete Host", "Deleted Host : " . $host->name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Host Deleted!", "Host successfully deleted.", "reload()")));
    }

    public function updateViewHost()
    {
        $data = array(
            "host" => Database::table("hosts")->where("id", input("hostid"))->first()
        );
        return view('extras/update_host', $data);
    }

    public function findAddViewHost()
    {

        $data = array(
            "hostname" => input("hostname"),
            "rooms" => Database::table("rooms")->get()
        );
        return view('extras/find_add_host', $data);
    }

    // get state names
    public function getRooms()
    {
        $json = Database::table("rooms")->where("building_id", input("countryID"))->get();
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function updateHost()
    {

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "bedid") {
                continue;
            }
            Database::table("hosts")->where("id", input("hostid"))->update(array($field->index => escape($field->value)));
        }

        // Action Log
        Customer::addActionLog("Host", "Update Host", "Changed Host information: ");

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Host was successfully updated", "reload()")));
    }

    public function roomHostStatus()
    {
        $user = Auth::user();

        return view('room/room_bed_status', compact("user"));
    }

    function getBeds()
    {
        $json = Database::table("beds")->where("room_id", input("stateID"))->get();
        //        foreach ($json as $bed){
        //            $student= Database::table("users")->find($bed->student_id);
        //            $bed->lease_start=
        //        }
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    function getHostData($room_id)
    {
        $hosts = array();
        $hosts_temps = Database::table("hosts")->where("room_id", $room_id)->get();
        foreach ($hosts_temps as $each_host) {
            $each_host_temp = $each_host;
            $hosts[] = $each_host_temp;
        }
        return $hosts;
    }

    function autoId()
    {

        $autoId = md5(uniqid(rand(), true));

        return $autoId;
    }

    public static function getBuildingName($building_id)
    {
        $building = Database::table("buildings")->where("id", $building_id)->first();
        return $building->name;
    }
    public static function getRoomName($room_id)
    {
        $room = Database::table("rooms")->where("id", $room_id)->first();
        return $room->name;
    }
}
