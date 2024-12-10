<?php
session_start();
include 'hotel_db.php';

if(isset($_REQUEST['getInfo'])){
    $option = $_REQUEST['getInfo'];
    switch($option){
        case 1:{
            $sql = "select StaffID, Name, Department.Type, Phone, Email from Staff inner join Department on Department.DepartmentID = Staff.DepartmentID";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 2:{
            $sid = $_REQUEST['sid'];
            $sql = "select StaffID, Name, Phone, Email, D.Type, D.DepartmentID from Staff cross join (select Department.Type, Department.DepartmentID from Department) as D
            where StaffID = '$sid' and not D.DepartmentID=-1";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 3:{
            $sql = "select DepartmentID, Type from Department where not DepartmentID=-1";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 4:{
            $sql = "select Department.DepartmentID, Department.Type, Department.Roles, S.c from Department left join 
            (select count(StaffID) as c, Staff.DepartmentID from Staff GROUP by DepartmentID) 
            as S on Department.DepartmentID = S.DepartmentID where not Department.DepartmentID=-1;";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 5:{
            $did = $_REQUEST['did'];
            $sql = "select DepartmentID, Type, Roles from Department where DepartmentID = '$did'";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 6:{
            $sql = "select RoomID, Description, Type, Price from Room";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 7:{
            $rid = $_REQUEST['rid'];
            $sql = "select RoomID, Description, Type, Price from Room where RoomID='$rid'";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 8:{
            $mid = $_SESSION['mid'];
            $sql = "select StaffID from Staff";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 9:{
            // $mid = $_SESSION['mid'];
            $sql = "select CourseID from Course";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
    }
    unset($option, $sid, $did);
}

if(isset($_REQUEST['u-id'])){
    $sid = $_REQUEST['u-id'];
    $sname = $_REQUEST['u-name'];
    $sphone = $_REQUEST['u-phone'];
    $smail = $_REQUEST['u-mail'];
    $sdep = $_REQUEST['u-dep'];

    $sql = "update Staff set Name='$sname', Phone='$sphone', Email='$smail', 
    DepartmentID='$sdep' where StaffID = '$sid'";
    $conn->query($sql);
    print_r(json_encode([1,3,1]));
    unset($sid, $sname, $sphone, $smail, $sdep);
    return;
}

if(isset($_REQUEST['fname'])){
    $spword = $_REQUEST['pword'];
    $sfname = $_REQUEST['fname'];
    $slname = $_REQUEST['lname'];
    $sphone = $_REQUEST['phone'];
    $smail = $_REQUEST['mail'];
    $sdep = $_REQUEST['dep'];

    $name = $sfname . " " . $slname;

    $sql = "insert into Staff (Password, Name, Phone, Email, DepartmentID)
    values ('$spword', '$name', '$sphone', '$smail', '$sdep')";
    $conn->query($sql);
    print_r(json_encode([1,1,1]));
    unset($spword, $sfname, $slname, $smail, $sdep, $sphone, $name);
    return;
}

if(isset($_REQUEST['d-sid'])){
    $sid = $_REQUEST['d-sid'];

    $sql = "delete from TrainingLog where StaffID='$sid'";
    $conn->query($sql);

    $sql = "delete from Management where StaffID='$sid'";
    $conn->query($sql);

    $sql = "delete from Staff where StaffID='$sid'";
    $conn->query($sql);

    print_r(json_encode([1,1,1]));
    unset($sid);
    return;
}

if(isset($_REQUEST['u-did'])){
    $did = $_REQUEST['u-did'];
    $dtype = $_REQUEST['u-dtype'];
    $droles = $_REQUEST['u-droles'];

    $sql = "update Department set Type='$dtype', Roles='$droles' where DepartmentID = '$did'";
    $conn->query($sql);
    print_r(json_encode([1,3,1]));
    unset($did, $dtype, $droles);
    return;
}

if(isset($_REQUEST['type'])){
    $dtype = $_REQUEST['type'];
    $droles = $_REQUEST['roles'];
    $sql = "insert into Department (Type, Roles) values ('$dtype', '$droles')";
    $conn->query($sql);
    print_r(json_encode([1,3,1]));
    unset($dtype, $droles);
    return;
}

if(isset($_REQUEST['d-did'])){
    $did = $_REQUEST['d-did'];

    $sql = "update Management set DepartmentID=-1 where ManagerID > 0 and DepartmentID = '$did'";
    $conn->query($sql);

    $sql = "update Staff set DepartmentID=-1 where StaffID > 0 and DepartmentID = '$did'";
    $conn->query($sql);

    $sql = "delete from Department where DepartmentID='$did'";
    $conn->query($sql);

    print_r(json_encode([1,1,1]));
    unset($did);
    return;
}

if(isset($_REQUEST['d-rid'])){
    $rid = $_REQUEST['d-rid'];
    $sql = "delete from Booking where RoomID='$rid'";
    $conn->query($sql);

    $sql = "delete from Room where RoomID='$rid'";
    $conn->query($sql);
    return;
}

// if(isset($_REQUEST['u-rid'])){
//     $rid = $_REQUEST['u-rid'];
//     $desc = $_REQUEST['u-desc'];
//     $type = $_REQUEST['u-rtype'];
//     $price = $_REQUEST['u-price'];

//     $sql = "select Description from Room where RoomID='$rid'";
//     $result = $conn->query($sql);
//     $records = $result->fetch_row();

//     $pattern = "/(.)+, /i";
//     $newDesc = preg_replace($pattern, );

//     $sql = "update Room set "
// }

if(isset($_REQUEST['a-sid'])){
    $sid = $_REQUEST['a-sid'];
    $cid = $_REQUEST['a-cid'];
    $sql = "insert into TrainingLog (Start, End, CourseID, StaffID, Status)
    values (CURRENT_DATE(), DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY), '$cid', '$sid', 'Incomplete')";
    $conn->query($sql);
    return;
}


?>