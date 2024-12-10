<?php
session_start();
include 'hotel_db.php';

if(isset($_REQUEST['getInfo'])){
    $option = $_REQUEST['getInfo'];
    switch($option){
        case 1:{
            $sql = "SELECT BookingID, CheckIn, CheckOut, RoomID, TransID, Guest.Name, DATEDIFF(CheckIn, CURRENT_DATE()), DATEDIFF(CheckOut, CURRENT_DATE()) 
            FROM Booking INNER JOIN Guest ON Guest.GuestID = Booking.GuestID";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 2:{
            $sql = "SELECT * FROM Room";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 3:{
            $sql = "SELECT BookingID FROM Booking INNER JOIN Room ON Room.RoomID = Booking.RoomID  WHERE DATEDIFF(CheckIn, CURRENT_DATE()) >= 0 AND Room.Status = 'Vacant'";
            // if checkin > current date, guest is checking in early
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 4:{
            $sql = "SELECT BookingID FROM Booking INNER JOIN Room ON Room.RoomID = Booking.RoomID  WHERE DATEDIFF(CURRENT_DATE(), CheckOut) <= 0 AND DATEDIFF(CheckIn, CURRENT_DATE()) <= 0 AND Room.Status = 'Occupied'";
            // if current date < checkout, guest is checking out early *for late check-in/out guest has to log on*
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 5:{
            $sid = $_SESSION['sid'];
            $sql = "SELECT TrainingLog.CourseID, Course.Title, Start, End, Status, LogID FROM TrainingLog INNER JOIN Course ON TrainingLog.CourseID=Course.CourseID WHERE StaffID='$sid'";
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            break;
        }
        case 6:{
            $sid = $_SESSION['sid'];
            $sql = "SELECT StaffID FROM Staff INNER JOIN Department ON Department.DepartmentID = Staff.DepartmentID WHERE Department.Type = 'Housekeeping' and StaffID='$sid'";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode($record));
            break;
        }
        case 7:{
            $sql = "select t.rid, t.rd, t.rs, t.rmci, v.rmco from (select Room.RoomID as rid, description rd, Room.status as rs, b.mci as rmci from Room left JOIN (select Booking.RoomID as rid, min(CheckIn) as mci from Booking 
            where datediff(CheckIn, CURRENT_DATE()) >= 0 GROUP by Booking.RoomID) as b on b.rid = Room.RoomID) as t inner join (select Room.RoomID as rid, description rd, Room.status as rs, b.mco as rmco from Room left JOIN (select Booking.RoomID as rid, min(CheckOut) 
            as mco from Booking where datediff(CheckOut, CURRENT_DATE()) >= 0 GROUP by Booking.RoomID) as b on b.rid = Room.RoomID) as v on v.rid=t.rid";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode($record));
            break;
        }
        case 8:{
            $sid = $_SESSION['sid'];
            $sql = "select Name, Phone, Email from Staff inner JOIN Management on Staff.StaffID=Management.StaffID where Management.DepartmentID IN (select Staff.DepartmentID FROM Staff where StaffID=1)";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode($record));
            break;
        }
        case 9:{
            $sql = "SELECT TransID, Name, Amount, Date, CreditCard FROM Transaction 
            INNER JOIN Guest ON Guest.GuestID = Transaction.GuestID
            WHERE NOT Transaction.CreditCard = -1 ";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode($record));
            break;
        }
        unset($option);
        return;
    }
}

if(isset($_REQUEST['id-search'])){     // bookings
        $bid = $_REQUEST['id-search'];
        $gname = $_REQUEST['name-search'];
        $start = $_REQUEST['start-range'];
        $end = $_REQUEST['end-range'];
        $and = false;
        $sql = "SELECT BookingID, CheckIn, CheckOut, RoomID, TransID, Guest.Name, DATEDIFF(CheckIn, CURRENT_DATE()), DATEDIFF(CheckOut, CURRENT_DATE()) 
            FROM Booking INNER JOIN Guest ON Guest.GuestID = Booking.GuestID"; // default query
        if(!($bid === '')){
            $sql .= " WHERE BookingID='$bid'";
            $and = true;
        }
        if(!($gname === '')){
            $sql .= $and?" AND":" WHERE";
            $sql .= " Guest.Name LIKE '%$gname%'";
            $and = true;
        }
        if(!($start === '')){
            $sql .= $and?" AND":" WHERE";
            $sql .= " DATEDIFF('$start', CheckIn) <= 0";
            $and = true;
        }
        if(!($end === '')){
            $sql .= $and?" AND":" WHERE";
            $sql .= " DATEDIFF('$end', CheckOut) >= 0";
        }
            $result = $conn->query($sql);
            $records = $result->fetch_all();
            print_r(json_encode($records));
            unset($bid, $gname, $start, $end, $and);
            return;
    
}

if(isset($_REQUEST['rid-search'])){     // rooms 
    $rid = $_REQUEST['rid-search'];
    $startp = $_REQUEST['start-price'];
    $endp = $_REQUEST['end-price'];
    $status = $_REQUEST['status-select'];
    $type = $_REQUEST['type-select'];
    $and = false;
    $sql = "SELECT * FROM Room"; // default query
    if(!($rid === '')){
        $sql .= " WHERE RoomID='$rid'";    
        $and = true;
    }
    if(!($startp === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Price >= '$startp'";
        $and = true;
    }
    if(!($endp === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Price <= '$endp'";
        $and = true;
    }
    if(!($status === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Status = '$status'";
        $and = true;
    }
    if(!($type === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Type = '$type'";
        $and = true;
    }
        $result = $conn->query($sql);
        $records = $result->fetch_all();
        print_r(json_encode($records));
        unset($rid, $startp, $endp, $and);
        return;

}

if(isset($_REQUEST['iv-search'])){     // invoices
    $tid = $_REQUEST['iv-search'];
    $gname = $_REQUEST['tname-search'];
    $start = $_REQUEST['tstart-range'];
    $end = $_REQUEST['tend-range'];
    $sql = "SELECT TransID, Name, Amount, Date, CreditCard FROM Transaction 
    INNER JOIN Guest ON Guest.GuestID = Transaction.GuestID
    WHERE NOT Transaction.CreditCard = -1 "; // default query

    if(!($tid === '')){
        $sql .= " AND TransID='$tid'";
    }
    if(!($gname === '')){
        $sql .= " AND Guest.Name LIKE '%$gname%'";
    }
    if(!($start === '')){
        $sql .= " AND DATEDIFF('$start', Date) <= 0";
    }
    if(!($end === '')){
        $sql .= " AND DATEDIFF(Date, '$end') <= 0";
    }
        $result = $conn->query($sql);
        $records = $result->fetch_all();
        print_r(json_encode($records));
        unset($tid, $gname, $start, $end);
        return;

}

if(isset($_REQUEST['r-update'])){
    $rid = $_REQUEST['r-update'];
    // $hk = $_REQUEST['r-hk-update'];
    $vac = $_REQUEST['r-v-update'];
    // if(!($vac === '')){
        $sql = "UPDATE Room SET Status='$vac' WHERE RoomID='$rid'";
        $conn->query($sql);
    // }
    // if(!($hk === '')){
    //     $sql = "SELECT Description FROM Room WHERE RoomID='$rid'";
    //     $result = $conn->query($sql);
    //     $record = $result->fetch_row();
    //     $pattern = "/, (.)+/i";
    //     $newDesc = preg_replace($pattern, ", ".$hk, $record);

    //     $sql = "UPDATE Room SET Description='$newDesc[0]' WHERE RoomID='$rid'";
    //     $conn->query($sql);
    // }
        print_r(json_encode([1,2,3]));
        unset($rid, $vac);
        return;
}

if(isset($_REQUEST['hkid'])){
    $rid = $_REQUEST['hkid'];
    $hk = $_REQUEST['r-rhk-update'];

        $sql = "SELECT Description FROM Room WHERE RoomID='$rid'";
        $result = $conn->query($sql);
        $record = $result->fetch_row();
        $pattern = "/, (.)+/i";
        $newDesc = preg_replace($pattern, ", ".$hk, $record);

        $sql = "UPDATE Room SET Description='$newDesc[0]' WHERE RoomID='$rid'";
        $conn->query($sql);
    
        print_r(json_encode([1,2,3]));
        unset($rid, $hk);
        return;
}

if(isset($_REQUEST['pci-update'])){
    $bid = $_REQUEST['pci-update'];
    $sql = "UPDATE Booking SET CheckIn=CURRENT_DATE() WHERE BookingID='$bid'";
    $result = $conn->query($sql);
    
    $sql = "UPDATE Room SET Status='Occupied' WHERE RoomID IN (SELECT RoomID FROM Booking WHERE BookingID='$bid')";
    $result = $conn->query($sql);
    print_r(json_encode([1,2,3]));
    unset($bid);
    return;
}
//     if(isset($_REQUEST['pco-update'])){
//         $bid = $_REQUEST['pco-update'];
//         $sql = "UPDATE Booking SET CheckOut=CURRENT_DATE() WHERE BookingID='$bid'";
//         $result = $conn->query($sql);
        
//         $sql = "UPDATE Room SET Status='Vacant' WHERE RoomID IN (SELECT RoomID FROM Booking WHERE BookingID='$bid')";
//         $result = $conn->query($sql);
//         print_r(json_encode([1,2,3]));
//         unset($bid);
//         return;
// }

if(isset($_REQUEST['pco-bid'])){
    $bid = $_REQUEST['pco-bid'];
        $sql = "SELECT TransID, Amount, Name FROM Transaction INNER JOIN Guest ON Transaction.GuestID = Guest.GuestID";
        $result = $conn->query($sql);
        $record = $result->fetch_row();
        print_r(json_encode($record));
    unset($bid);
    return;
}

if(isset($_REQUEST['tid'])){
    $tid = $_REQUEST['tid'];
    $cno = $_REQUEST['cno'];

    $sql = "UPDATE Transaction SET CreditCard='$cno', Date=CURRENT_DATE() WHERE TransID='$tid'";
    $conn->query($sql);

    $sql = "UPDATE Booking SET CheckOut=CURRENT_DATE() WHERE BookingID IN (SELECT BookingID FROM Transaction WHERE TransID='$tid')";
    $conn->query($sql);

    $sql = "UPDATE Room SET Status='Vacant' WHERE RoomID IN 
            (SELECT RoomID FROM Booking WHERE BookingID IN 
                (SELECT BookingID FROM Transaction WHERE TransID='$tid'))";
    $conn->query($sql);

    print_r(json_encode([1,2,3]));
    unset($tid, $cno);
    return;
}

if(isset($_REQUEST['lc'])){
    $logid = $_REQUEST['lc'];

    $sql = "UPDATE TrainingLog SET Status='Complete' WHERE LogID='$logid'";
    $conn->query($sql);
    print_r(json_encode([1,2,3]));
    unset($logid);
    return;
}

?>