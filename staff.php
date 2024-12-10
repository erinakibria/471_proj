<?php
    session_start();
    include 'hotel_db.php';

    if(isset($_SESSION['sid'])){
        $sid = $_SESSION['sid'];    // staff id, to be used in actions.php as well
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
                    /* flex-direction: column; */
                    justify-content: center;
                    /* align-items: center; */
                    font-family: sans-serif;
                    font-size: 17px

                }

                .intro .side{
                    width: 20%;
                    background-color: #f4cccc;
                    display: flex;
                    flex-direction: column;
                    gap: 30px;
                    /* justify-content: center; */
                    align-items: center;
                    height: 780px;
                    /* padding-top: 30px; */
                }

                .intro .side button{
                    width: 100px;
                    margin-top: 20px;
                }

                .middle{
                    display: flex;
                    width: 60%;
                    /* align-items: center; */
                    justify-content: center;
                    /* flex-direction: column; */
                }

                #sid_form{
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                    align-items: center;
                }

                #sid_form button{
                    width: 50%;
                }

                .upper{
                    font-family: sans-serif;
                    display: flex;
                    gap: 470px;
                }

                table, th, td {
                    font-family: sans-serif;
                     border:1px solid black;
                }

                table{
                    margin-top: 40px;
                    margin-bottom: 40px;
                }

                #label{
                    display: inline;
                }

                .display{
                    display: none;
                }

                #filter-panel-vv, #filter-panel-vb, #filter-panel-pci, #filter-panel-iv{
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

                #course-finish{
                    display: none;
                }
                
            </style>
            <script src='staff.js'></script>
        </head>
        <body>
            <div class='upper'>
            <form action='home.php' method='post'>
                    <input id='logout' class='logout' type='submit' name='logout' value='Logout'>
            </form>
            <?php
                        $_SESSION['sid'] = $sid;
                        $sql = "SELECT Name, DepartmentID FROM Staff WHERE StaffID='$sid'";
                        $result = $conn->query($sql);
                            $records = $result->fetch_row();
                            echo "<div>Welcome to your employee dashboard, $records[0] (Department $records[1])</div>";
                    ?>
            </div>
              <div class='intro'>
                <div class='side'>
                    <button id='vb' onclick='displaySelection(this.id)'>Bookings</button>
                    <button id='vv' onclick='displaySelection(this.id)'>Vacancies</button>
                    <button id='pci' onclick='displaySelection(this.id)'>Check-in</button>
                    <button id='pco' onclick='displaySelection(this.id)'>Check-out</button>

                </div>
                <div class='middle' id='middle'>
                    <div id='label'>Select a menu item to begin</div>
                    <div class='display' id='display_vb'>
                        <div class='filters'>
                            <button onclick="showFilters('vb')">View filter panel</button>
                            <div id='filter-panel-vb'>
                                <form action='' method='post' id='vb-filter-form'>
                                    <div>
                                        <label for='id-search'>Booking ID:</label>
                                        <input name='id-search' id='id-search' type='number' min=1></input>
                                    </div>
                                    <div>
                                        <label for='name-search'>Guest name:</label>
                                        <input name='name-search' id='name-search'></input>
                                    </div>
                                    <div>
                                        <label for='start-range'>From (earliest check-in):</label>  
                                        <!-- earliest check in date to display -->
                                        <input name='start-range' type='date'></input>
                                    </div>
                                    <div>
                                        <label for='end-range'>To (latest check-out):</label>
                                        <!-- latest check-out date to display -->
                                        <input name='end-range' type='date'></input>
                                    </div>
                                    <div>
                                        <button onclick="applyFilters('vb', 7)" type='submit'>Apply filters</button>
                                        <button type='reset'>Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id='vb-table'>
                            <tr>
                              <th>Booking ID</th>
                              <th>Check-in Date</th>
                              <th>Check-out Date</th>
                              <th>Room ID</th>
                              <th>Transaction ID</th>
                              <th>Guest Name</th>    
                              <th>Status</th> 
                            </tr>
                        </table>
                    </div> <!-- display vb ends -->
                    <div class='display' id='display_vv'>
                        <div class='filters'>
                            <button onclick="showFilters('vv')">View filter panel</button>
                            <div id='filter-panel-vv'>
                                <form action='' method='post' id='vv-filter-form'>
                                    <div>
                                        <label for='rid-search'>Room ID:</label>
                                        <input name='rid-search' id='rid-search' type='number' min=1></input>
                                    </div>
                                    <div>
                                        <label for='status-select'>Status:</label>
                                        <select name="status-select" id="status-select">
                                            <option value=""></option>
                                            <option value="Vacant">Vacant</option>
                                            <option value="Occupied">Occupied</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for='type-select'>Type:</label>
                                        <select name="type-select" id="type-select">
                                            <option value=""></option>
                                            <option value="Standard">Standard</option>
                                            <option value="Deluxe">Deluxe</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for='start-price'>Min price:</label>     
                                        <!-- probably should just be on guest side -->
                                        <input name='start-price' type='price'></input>
                                    </div>
                                    <div>
                                        <label for='end-price'>Max price:</label>
                                        <!-- latest check-out date to display -->
                                        <input name='end-price' type='price'></input>
                                    </div>
                                    <div>
                                        <button onclick="applyFilters('vv', 5)">Apply filters</button>
                                        <button type='reset'>Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id='vv-table'>
                            <tr>
                              <th>Room ID</th>
                              <th>Description</th>
                              <th>Type</th>
                              <th>Price</th>
                              <th>Status</th>
                            </tr>
                        </table>

                        <div id='update-v'>
                            <form id='vv-update-form' action='' method='post'>
                                <div>
                                    <label for='r-update'>RoomID to update:</label>
                                    <select required name="r-update" id="r-update">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <table id='update-v-table'>
                                <tr>
                                    <!-- <th>Housekeeping status</th> -->
                                    <th>Vacancy status</th>
                                </tr>
                                <tr>
                                    <!-- <th>
                                        <select name="r-hk-update" id="r-hk-update">
                                            <option value=""></option>
                                            <option value="Cleaned">Cleaned</option>
                                            <option value="Housekeeping in progress">Housekeeping in progress</option>
                                            <option value="Housekeeping needed">Housekeeping needed</option>
                                        </select>
                                    </th> -->
                                    <th>
                                        <select required name="r-v-update" id="r-v-update">
                                            <option value=""></option>
                                            <option value="Vacant">Vacant</option>
                                            <option value="Occupied">Occupied</option>
                                        </select>
                                    </th>
                                </tr>
                                </table>
                                <button onclick="update('vv')">Update</button>
                            </form>
                        </div>
                    </div> <!-- display vv ends -->
                    <div class='display' id='display_pci'>
                        <form action='' method='post' id='pci-update-form'>
                            <label for='pci-update'>BookingID to check-in:</label>
                            <select required name="pci-update" id="pci-update">
                                <option value=""></option>
                            </select>
                            <div>
                                <button onclick="update('pci')">Check-in</button>
                            </div>
                        </form>
                        <p id='pci-success'></p>
                    </div> <!-- display pci ends -->
                    <div class='display' id='display_pco'>
                        <!-- <form action='' method='post' id='pco-conf-form'> -->
                            <div>
                                <label for='pco-update'>BookingID to check-out:</label>
                                <select name="pco-update" id="pco-update" required>
                                    <option value="" ></option>
                                </select>
                            </div>
                            <button onclick="displaySelection('pco-conf')" id='pco-confirm'>Select</button>
                        <!-- </form> -->
                        <form action='' method='post' id='pco-update-form'>
                            <table id='update-t-table'>
                                <tr>
                                    <th>Guest name</th>
                                    <th>Card number</th>
                                    <th>Amount</th>
                                    <th>Transaction ID</th>
                                </tr>
                                <tr>
                                    <td id='t-guest'></td>
                                    <td><input required name='t-card'id='t-card' type='number'></input></td>
                                    <td id='t-amt'></td>
                                    <td id='t-id'></td>
                                </tr>
                            </table>
                            <div>
                                <button onclick="update('pco')">Check-out</button>
                            </div>
                        </form>
                        <p id='pco-success'></p>
                    </div> <!-- display pco ends -->
                    <div class='display' id='display_cc'>
                        <table id='course-table'>
                            <tr>
                                <th>Course ID</th>
                                <th>Course name</th>
                                <th>Assigned</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                            <!-- <tr>
                                <td id='t-guest'></td>
                                <td><input required name='t-card'id='t-card' type='number'></input></td>
                                <td id='t-amt'></td>
                                <td id='t-id'></td>
                            </tr> -->
                        </table>
                        <div>
                            <label for='cid'>Course to complete:</label>
                            <select name="cid" id="cid" required>
                                <option value="" ></option>
                            </select>
                            <button onclick='displayCourse()'>Load</button>
                            <p id='course-content'></p>
                            <p id='hidden-logid' style='display: none;'></p>
                            <button onclick="update('cc')" id='course-finish'>Finish</button>
                        </div>
                    </div> <!-- display cc ends -->
                    <div class='display' id='display_hk'>
                        <table id='hk-table'>
                            <tr>
                                <th>Room ID</th>
                                <th>Housekeeping status</th>
                                <th>Vacancy status</th>
                                <th>Next check-in:</th>
                                <th>Next check-out:</th>
                            </tr>
                        </table>
                        <div>
                            <form id='hk-update-form' action='' method='post'>
                                <div>
                                <label for='hkid'>Room to update:</label>
                                <select required name="hkid" id="hkid" required>
                                    <option value="" ></option>
                                </select>
                                </div>
                                <table id='update-hk-table'>
                                <tr>
                                    <th>Housekeeping status</th>
                                </tr>
                                <tr>
                                    <th>
                                        <select required name="r-rhk-update" id="r-rhk-update">
                                            <option value=""></option>
                                            <option value="Cleaned">Cleaned</option>
                                            <option value="Housekeeping in progress">Housekeeping in progress</option>
                                            <option value="Housekeeping needed">Housekeeping needed</option>
                                        </select>
                                    </th>
                                </tr>
                                </table>
                                <button onclick="update('hk')">Update</button>
                            </form>
                        </div>
                    </div> <!-- display hk ends -->
                    <div class='display' id='display_iv'>
                        <div class='filters'>
                            <button onclick="showFilters('iv')">View filter panel</button>
                            <div id='filter-panel-iv'>
                                <form action='' method='post' id='iv-filter-form'>
                                    <div>
                                        <label for='iv-search'>Transaction ID:</label>
                                        <input name='iv-search' id='iv-search' type='number' min=1></input>
                                    </div>
                                    <div>
                                        <label for='tname-search'>Guest name:</label>
                                        <input name='tname-search' id='tname-search'></input>
                                    </div>
                                    <div>
                                        <label for='tstart-range'>From (earliest):</label>  
                                        <!-- earliest check in date to display -->
                                        <input name='tstart-range' type='date'></input>
                                    </div>
                                    <div>
                                        <label for='tend-range'>To (latest):</label>
                                        <!-- latest check-out date to display -->
                                        <input name='tend-range' type='date'></input>
                                    </div>
                                    <div>
                                        <button onclick="applyFilters('iv', 5)" type='submit'>Apply filters</button>
                                        <button type='reset'>Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id='iv-table'>
                            <tr>
                              <th>Transaction ID</th>
                              <th>Guest Name</th>
                              <th>Amount</th>
                              <th>Date completed</th>
                              <th>Card number</th>    
                            </tr>
                        </table>
                    </div> <!-- display iv ends -->
                    <div class='display' id='display_help'>
                        <table id='help-table'>
                            <tr>
                                <th>Manager name</th>
                                <th>Phone number</th>
                                <th>Email</th>
                            </tr>
                        </table>
                        <p>Please use the above information to contact your department manager(s).</p>
                    </div> <!-- display help ends -->
                </div> <!-- mid sec ends -->
                <!-- If a user is also a manager, only staff permissions will be displayed upon clicking continue
                For manager permissions, user will have to select the management role on the home page-->
                <div class='side'>
                    <button id='cc' onclick='displaySelection(this.id)'>Course catalouge</button>
                    <button id='hk' onclick='displaySelection(this.id)'>Housekeeping assignments</button>    
                    <!-- this one is usable if employee is in housekeeping department -->
                    <!-- current/expected bills -->
                    <button id='iv' onclick='displaySelection(this.id)'>Invoices</button>
                    <!-- past completed transactiins -->
                    <button id='help' onclick='displaySelection(this.id)'>Help</button>
                </div>
            </div>
        </body>
    </html>