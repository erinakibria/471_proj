<?php
session_start();
include 'hotel_db.php';

if(isset($_REQUEST['getInfo'])){
    $code = $_REQUEST['getInfo'];
    switch($code){
        case 1:{
            $sql = "select t.rid, t.rd, t.rs, t.Type, t.Price, v.rmco from 
                (select Room.Type, Room.Price, Room.RoomID as rid, description rd, Room.status as rs, b.mci as rmci from Room 
                left JOIN (select Booking.RoomID as rid, min(CheckIn) as mci from Booking 
                where datediff(CheckIn, CURRENT_DATE()) >= 0 
                GROUP by Booking.RoomID) as b on b.rid = Room.RoomID) as t 
                inner join (select Room.RoomID as rid, description rd, Room.status as rs, b.mco as rmco from Room 
                left JOIN (select Booking.RoomID as rid, min(CheckOut) as mco from Booking where datediff(CheckOut, CURRENT_DATE()) >= 0 
                GROUP by Booking.RoomID) as b on b.rid = Room.RoomID) as v on v.rid=t.rid";
                // the whole query is not applicable for what is selected, just wrote like this to save time
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode($record));
            break;
        }
        case 2:{
            $gid = $_SESSION['gid'];
            $sql = "select BookingID from Booking inner join Transaction on Booking.TransID=Transaction.TransID where Booking.GuestID='$gid' and not CreditCard=-1";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode(($record)));
            break;
        }
        case 3:{
            $cid = $_REQUEST['ci-date'];
            $cod = $_REQUEST['co-date'];
            $rid = $_REQUEST['rid'];
            $sql = "select BookingID from Booking where 
            (Booking.CheckIn between '$cid' and DATE_ADD('$cod', INTERVAL -1 DAY)
            or Booking.CheckOut between date_add('$cid', INTERVAL 1 day)and '$cod'
            or '$cid' between Booking.CheckIn and date_add(Booking.CheckOut, interval -1 day)
            or '$cod' between date_add(Booking.CheckIn, interval 1 day) and Booking.CheckOut)
            and Booking.RoomID='$rid'";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode(($record)));
            break;
        }
        case 4:{
            $gid = $_SESSION['gid'];
            $sql = "select BookingID, CheckIn, CheckOut, RoomID, DATEDIFF(CheckIn, CURRENT_DATE()), DATEDIFF(CheckOut, CURRENT_DATE())
            from Booking where GuestID = '$gid'";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode(($record)));
            break;
        }
        case 5:{
            $gid = $_SESSION['gid'];
            $sql = "select Room.RoomID, Room.Description from Room inner join Booking on Room.RoomID = Booking.RoomID
            where DATEDIFF(Booking.CheckIn, Current_date()) <= 0 and DATEDIFF(Booking.CheckOut, CURRENT_DATE()) > 0 and Booking.GuestID = '$gid'";
            $result = $conn->query($sql);
            $record = $result->fetch_all();
            print_r(json_encode(($record)));
            break;
        }

    }
    unset($code, $gid, $gpword, $cid, $cod, $rid);
    return;
}

if(isset($_REQUEST['final-rid'])){
    // *** if new guest, guest record must be created before entering here ***
    $gid = $_SESSION['gid'];
    $rid = $_REQUEST['final-rid'];
    $cid = $_REQUEST['final-cid'];
    $cod = $_REQUEST['final-cod'];
    $amt = $_REQUEST['final-amt'];
    $pay = $_REQUEST['final-pay'];
    $tid = 0;

    if($pay == 'Now'){
        $cno = $_REQUEST['final-cno'];
        $sql = "insert into Transaction (Date, Amount, CreditCard, GuestID) values
        (CURRENT_DATE(), '$amt', '$cno', '$gid')";
        $conn->query($sql);
        
        $sql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($sql);
        $record = $result->fetch_row();

        $tid = $record[0];
    }
    else{
        $sql = "insert into Transaction (Date, Amount, CreditCard, GuestID) values
        (CURRENT_DATE(), '$amt', -1, '$gid')";
        $conn->query($sql);
        
        $sql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($sql);
        $record = $result->fetch_row();

        $tid = $record[0];

    } 


    $sql = "insert into Booking (CheckIn, CheckOut, RoomID, TransID, GuestID)
    values ('$cid', '$cod', '$rid', '$tid', '$gid')";
    $conn->query($sql);
    
    $sql = "SELECT LAST_INSERT_ID()";
    $result = $conn->query($sql);
    $record = $result->fetch_row();

    print_r(json_encode($record));
    unset($gid, $rid, $cid, $cod, $amt, $pay, $cno, $tid);
    return;

}

if(isset($_REQUEST['dstart-range'])){     // bookings
    $start = $_REQUEST['dstart-range'];
    $end = $_REQUEST['dend-range'];
    $type = $_REQUEST['type-search'];
    $minprice = $_REQUEST['pstart-range'];
    $maxprice = $_REQUEST['pend-range'];
    $and = false;
    $sql = "select RoomID, Description, Type, Price from Room";  // base query

    if(!($start === '') && !($end === '')){
        $sql .= " where Room.RoomID NOT IN 
        (select Booking.RoomID from Booking where (Booking.CheckIn between '$start' 
        and DATE_ADD('$end', INTERVAL -1 DAY) 
        or Booking.CheckOut between date_add('$start', INTERVAL 1 day)and '$end' 
        or '$start' between Booking.CheckIn and date_add(Booking.CheckOut, interval -1 day) 
        or '$end' between date_add(Booking.CheckIn, interval 1 day) and Booking.CheckOut))";
        $and = true;
    }
    if(!($type === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Type='$type'";
        $and = true;
    }
    if(!($minprice === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Price >= '$minprice'";
        $and = true;
    }
    if(!($maxprice === '')){
        $sql .= $and?" AND":" WHERE";
        $sql .= " Price <= '$maxprice'";
    }
        $result = $conn->query($sql);
        $records = $result->fetch_all();
        print_r(json_encode($records));
        unset($type, $minprice, $maxprice, $start, $end, $and);
        return;

}

if(isset($_REQUEST['rc-select'])){
    $bid = $_REQUEST['rc-select'];
    $sql = "select Booking.BookingID, Booking.CheckIn, Booking.CheckOut, Booking.TransID, Transaction.Date, 
    Transaction.Amount, Transaction.CreditCard from Booking 
    inner join Transaction on Transaction.TransID = Booking.TransID where Booking.BookingID='$bid'";
    $result = $conn->query($sql);
    $record = $result->fetch_row();
    print_r(json_encode($record));
    unset($bid);
    return;
}

?>