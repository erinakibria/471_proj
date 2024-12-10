<?php
    session_start();
    include 'hotel_db.php';

    if(isset($_SESSION['gid'])){
        $gid = $_SESSION['gid'];    // staff id, to be used in actions.php as well
    }
    else {
        header("Location: home.php");
        die();
    }
?>

<DOCTYPE! html>
    <html>
        <head>
            <style>
                .intro{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    font-family: sans-serif;
                    /* font-size: 20px; */
                    margin-bottom: 40px;
                }

                .room-div{
                    /* display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center; */
                    display: flex;
                    flex-direction: column;
                    font-family: sans-serif;
                    gap: 40px;
                    margin-left: 40px;
                    width: 70%;

                }

                .room img{
                    width: 200px;
                    height: auto;
                    margin-right: 40px;
                }

                .room {
                    margin-bottom: 30px;
                    display: flex;
                    border: solid;
                    padding: 20px;
                    border-width: 5px;
                    border-color: lightpink;
                }

                #filter-panel-br{
                    font-family: sans-serif;
                    width: 300px;
                    height: auto;
                    background-color: gray;
                    margin-top: 30px;
                    color: white;
                    font-size: 15px;
                    padding: 10px;
                    display: none;
                }

                .room-price{
                    /* margin-left: 0%; */
                    /* float: right; */
                }

                .room-info{
                    margin-left: 3%;
                    margin-right: 25%;
                }

                #book-button{
                    float: right;
                    display: none;
                }

                .room-lab p{
                    font-weight: bold;
                }

                .room:hover{
                    background-color: lightpink;
                }

                #book{
                    font-family: sans-serif;

                    display: flex;
                }
                
                #booking-form{
                    margin-top: 60px;
                    margin-left: 60px;
                    display:none;
                }

                #new-acc, #have-acc{
                    display: none;
                    margin-top: 20px;
                }

                #book-form{
                    display: none;
                }

                .top-login{
                    float: right;
                    margin-top: 30px;
                    margin-right: 40px;
                }

                #guest-panel{
                    background-color: lightpink;
                    height: 100%;
                    width: 20%;
                    margin-left: 80px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    margin-top: 70px;
                    gap: 50px;
                }

                #guest-panel button{
                    width: 50%;
  
                }

                #reciept-div, #conf-div, #stat-div{
                    display: none;
                    font-family: sans-serif;
                    margin-left: 40px;
                    width: 70%;
                }

                #receipt{
                    border: solid;
                    font-family: 'Courier New', monospace;
                    width: 30%;
                    display: none;
                    flex-direction: column;
                    align-items: center;
                }
                table, th, td {
                    font-family: sans-serif;
                     border:1px solid black;
                }

                table{
                    margin-top: 40px;
                    margin-bottom: 40px;
                }
                

            </style>
            <script src='guest.js'></script>

        </head>
        <body>
            <a href='home.php'>Logout</a>
            <div class='intro'>
                <h2>Guest Dashboard</h2>
                <!-- Guests won't be prompted to enter their id's unless they're trying to view info about their booking 
                or if they're a returning user who's entering their guest id instead of getting a new one assigned during
                checkout/booking -->
            </div>
            <div id='book'>
                <div id='reciept-div'>
                    <form id='rc-form' action='' method='post'>
                        <label for='rc-select'>Booking ID: </label>
                        <select required id='rc-select' name='rc-select'>
                            <option value=""></option>
                        </select>
                        <button onclick='showReciept()'>Load Receipt</button>
                    </form>
                    <div id='receipt'>

                    </div>
                </div>
                <div id='conf-div'>
                    <table id='conf-table'>
                        <tr>
                            <th>Booking ID</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Room ID</th>
                            <th>Status</th>
                        </tr>
                    </table>
                    A customer service representative will request your booking ID when checking you in and out.
                </div>
                <div id='stat-div'>
                    Active bookings:
                    <table id='stat-table'>
                        <tr>
                            <th>Room ID</th>
                            <th>Housekeeping status</th>
                        </tr>
                    </table>
                    *Status reports are periodically updated, but may not be 100% accurate. Ask a customer service representative for more details.
                </div>
                <div class='room-div' id='room-div'>
                    <div class='filters'>
                        <button onclick="showFilters('br')">Filter rooms</button>
                        <div id='filter-panel-br'>
                            <form action='' method='post' id='br-filter-form'>
                                <div>
                                    <label for='dstart-range'>Check-in:</label>  
                                    <!-- earliest check in date to display -->
                                    <input name='dstart-range' type='date'></input>
                                </div>
                                <div>
                                    <label for='dend-range'>Check-out:</label>
                                    <!-- latest check-out date to display -->
                                    <input name='dend-range' type='date'></input>
                                </div>
                                <p>*Both of the above must be set for date filters to work*</p>
                                <div>
                                    <label for='type-search'>Type:</label>
                                    <select name='type-search' id='type-search'>
                                        <option value=""></option>
                                        <option value="Standard">Standard</option>
                                        <option value="Deluxe">Deluxe</option>
                                    </select>
                                </div>
                                <div>
                                    <label for='pstart-range'>Min price ($):</label>  
                                    <!-- earliest check in date to display -->
                                    <input name='pstart-range' type='number'></input>
                                </div>
                                <div>
                                    <label for='pend-range'>Max price ($):</label>
                                    <!-- latest check-out date to display -->
                                    <input name='pend-range' type='number'></input>
                                </div>
                                <div>
                                    <button type='submit'>Apply filters</button>
                                    <button type='reset'>Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id='display-rooms'>
                    </div>
                                <!-- end of display-rooms -->
                </div>
                <!-- end of room div -->
                <div id='booking-form'>
                    <?php
                        $sql = 'Select CURRENT_DATE(), DATE_ADD(CURRENT_DATE(), interval 1 day)';
                        $result = $conn->query($sql);
                        $record = $result->fetch_row();
                            echo "
                            <form id='book-form' action='' method='post' style='display: inline;'>
                                <div>
                                    <label>Check-in date:</label>
                                    <input type='date' min=$record[0] id='ci-date' required name='ci-date'></input>
                                </div>
                                <div>
                                    <label>Check-out date:</label>
                                    <input type='date' min=$record[1] id='co-date' required name='co-date'></input>
                                </div>
                                <button>Proceed</button>
                                <button type='button' onclick='back()'>Back</button>
                            </form>
                            ";
                    ?>
                    <div id='booking-conf'>
                        <!-- <p>Confirm details: </p>
                        <div>
                            <label>RoomID: </label>
                            <input readonly id='conf-id' value=""></input>
                        </div>
                        <div>
                            <label>Check-in: </label>
                            <input readonly id='conf-id'></input>
                        </div>
                        <div>
                            <label>Check-out: </label>
                            <input readonly id='conf-id'></input>
                        </div>
                        <div>
                            <label>Payment: </label>
                            <input readonly id='conf-id'></input>
                        </div>
                        <button onclick='confirm(2)'>Book</button> -->
                    </div>
                </div>
                    <!-- end of booking form div -->
                <?php
                        $sql = "Select Name from Guest where GuestID='$gid'";
                        $result = $conn->query($sql);
                        $record = $result->fetch_row();
                        echo "
                        <div id='guest-panel'>
                            <p id='welcome-msg'>Welcome $record[0] (GuestID $gid)</p>
                            <button id='reciept' onclick='displaySelection(this.id)'>Receipts</button><br>
                            <button id='conf' onclick='displaySelection(this.id)'>Booking confirmation</button><br>
                            <button id='stat' onclick='displaySelection(this.id)'>Room status</button><br>
                            <button id='room' onclick='displaySelection(this.id)'>View vacancies</button><br>
                        </div>
                        ";
                ?>
                <!-- end of guest (logged in) panel -->
            </div>
            <!-- end of booking div -->
        </body>
    </html>