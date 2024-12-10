<?php
    session_start();
    include 'hotel_db.php';

    if(isset($_SESSION['mid'])){
        $mid = $_SESSION['mid'];    // staff id, to be used in actions.php as well
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

                #course-finish, #si-create-form, #si-update-form, #si-delete-form{
                    display: none;
                }
                
            </style>
            <script src='management.js'></script>
        </head>
        <body>
            <div class='upper'>
            <form action='home.php' method='post'>
                    <input id='logout' class='logout' type='submit' name='logout' value='Logout'>
            </form>
            <?php
                $mid = $_SESSION['mid'];
                $sql = "SELECT Staff.Name, Management.DepartmentID FROM Management inner join staff on Staff.StaffID = Management.StaffID WHERE Management.ManagerID='$mid'";                
                $result = $conn->query($sql);
                $records = $result->fetch_row();
                echo "<div>Welcome to your manager dashboard, $records[0] (Department $records[1])</div>";
            ?>
            </div>
              <div class='intro'>
                <div class='side'>
                    <button id='si' onclick='displaySelection(this.id)'>Staff info</button>
                    <button id='di' onclick='displaySelection(this.id)'>Department info</button>
                    <button id='ri' onclick='displaySelection(this.id)'>Room info</button>
                    <button id='ac' onclick='displaySelection(this.id)'>Assign course</button>

                </div>
                <div class='middle' id='middle'>
                    <div id='label'>Select a menu item to begin</div>
                    <div class='display' id='display_si'>
                        <table id='si-table'>
                            <tr>
                              <th>Staff ID</th>
                              <th>Name</th>
                              <!-- <th>ManagerID</th> -->
                              <!-- <th>Manager Name</th> -->
                              <th>Phone</th>    
                              <th>Email</th> 
                              <th>Department</th>
                            </tr>
                        </table>
                        <button onclick="actionDisplay('si-update-form')">Update existing</button>
                        <button onclick="actionDisplay('si-create-form')">Create new</button>
                        <button onclick="actionDisplay('si-delete-form')">Delete</button>
                        <div>
                            <form id='si-delete-form' action='' method='post'>
                                <div>
                                    <label for='d-sid'>StaffID to delete:</label>
                                    <select required name="d-sid" id="d-sid" required>
                                        <option value="" ></option>
                                    </select>
                                </div>
                                <button>Delete</button>
                            </form>
                            <form id='si-update-form' action='' method='post'>
                                <div>
                                    <label for='sid'>StaffID to update:</label>
                                    <select required name="sid" id="sid" required>
                                        <option value="" ></option>
                                    </select>
                                    <button type='button' onclick="displaySelection('si-spec')">Continue</button>
                                    </div>
                                    <div id='update-sid'>
                                    <div>
                                        <label>Staff ID:</label>
                                        <input name='u-id' id='u-id'readonly>
                                    </div>
                                    <div>
                                        <label>Name:</label>
                                        <input name='u-name' id='u-name'required>
                                    </div>
                                    <div>
                                        <label>Phone:</label>
                                        <input name='u-phone' id='u-phone'required type='number' min='1'>
                                    </div>
                                    <div>
                                        <label>Email:</label>
                                        <input name='u-mail' id='u-mail' required type='email'>
                                    </div>
                                    <div>
                                        <label>Department:</label>
                                        <select name='u-dep' id='dep-sel' required>
                                        </select>
                                    </div>    
                                </div>
                                <button>Update</button>
                            </form>
                            <form id='si-create-form' action='' method='post'>
                                <div>
                                    <div>
                                        <label>First name:</label>
                                        <input name='fname' id='fname'required>
                                    </div>
                                    <div>
                                        <label>Last Name:</label>
                                        <input name='lname' id='lname'required>
                                    </div>
                                    <div>
                                        <label>Password:</label>
                                        <input name='pword' id='pword'required>
                                    </div>
                                    <div>
                                        <label>Phone:</label>
                                        <input name='phone' id='phone'required type='number' min='1'>
                                    </div>
                                    <div>
                                        <label>Email:</label>
                                        <input name='mail' id='mail' required type='email'>
                                    </div>
                                    <div>
                                        <label>Department:</label>
                                        <select name='dep' id='dep' required>
                                        </select>
                                    </div>    
                                </div>
                                <button>Create</button>
                            </form>
                        </div>
                    </div> <!-- display vb ends -->
                    <div class='display' id='display_di'>
                        <table id='di-table'>
                            <tr>
                              <th>Department ID</th>
                              <th>Type</th>
                              <!-- <th>ManagerID</th> -->
                              <!-- <th>Manager Name</th> -->
                              <th>Roles</th>    
                              <th>Number of employees</th> 
                            </tr>
                        </table>
                        <button onclick="actionDisplay('di-update-form')">Update existing</button>
                        <button onclick="actionDisplay('di-create-form')">Create new</button>
                        <button onclick="actionDisplay('di-delete-form')">Delete</button>

                        <div>
                            <form id='di-delete-form' action='' method='post'>
                                <div>
                                    <label for='d-did'>DepartmentID to delete:</label>
                                    <select required name="d-did" id="d-did" required>
                                        <option value="" ></option>
                                    </select>
                                </div>
                                <button>Delete</button>
                            </form>
                            <form id='di-update-form' action='' method='post'>
                                <div>
                                    <label for='did'>DepartmentID to update:</label>
                                    <select required name="did" id="did" required>
                                        <option value="" ></option>
                                    </select>
                                    <button type='button' onclick="displaySelection('di-spec')">Continue</button>
                                    </div>
                                    <div id='update-did'>
                                    <div>
                                        <label>Department ID:</label>
                                        <input name='u-did' id='u-did'readonly>
                                    </div>
                                    <div>
                                        <label>Type:</label>
                                        <input name='u-dtype' id='u-dtype'required>
                                    </div>
                                    <div>
                                        <label>Roles:</label>
                                        <input name='u-droles' id='u-droles'required>
                                    </div>   
                                </div>
                                <button>Update</button>
                            </form>
                            <form id='di-create-form' action='' method='post'>
                                <div>
                                    <div>
                                        <label>Type:</label>
                                        <input name='type' id='type'required>
                                    </div>
                                    <div>
                                        <label>Roles:</label>
                                        <input name='roles' id='roles'required>
                                    </div>  
                                </div>
                                <button>Create</button>
                            </form>
                        </div>
                    </div> <!-- display di ends -->
                    <div class='display' id='display_ri'>
                        <table id='ri-table'>
                            <tr>
                              <th>Room ID</th>
                              <th>Description</th>
                              <!-- <th>ManagerID</th> -->
                              <!-- <th>Manager Name</th> -->
                              <th>Type</th>    
                              <th>Price</th> 
                            </tr>
                        </table>
                        <button onclick="actionDisplay('di-update-form')">Update existing</button>
                        <button onclick="actionDisplay('di-create-form')">Create new</button>
                        <button onclick="actionDisplay('di-delete-form')">Delete</button>

                        <div>
                            <form id='ri-delete-form' action='' method='post'>
                                <div>
                                    <label for='d-rid'>RoomID to delete:</label>
                                    <select required name="d-rid" id="d-rid" required>
                                        <option value="" ></option>
                                    </select>
                                </div>
                                <button>Delete</button>
                            </form>
                            <form id='ri-update-form' action='' method='post'>
                                <div>
                                    <label for='rid'>RoomID to update:</label>
                                    <select required name="rid" id="rid" required>
                                        <option value="" ></option>
                                    </select>
                                    <button type='button' onclick="displaySelection('ri-spec')">Continue</button>
                                    </div>
                                    <div id='update-rid'>
                                    <div>
                                        <label>Room ID:</label>
                                        <input name='u-rid' id='u-rid'readonly>
                                    </div>
                                    <div>
                                        <label>Description:</label>
                                        <select name='u-desc' id='u-desc'required>
                                            <option value=""></option>
                                            <option value="Two Doubles">Two Doubles</option>
                                            <option value="Junior">Junior</option>
                                            <option value="Queen">Queen</option>
                                            <option value="King">King</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Type:</label>
                                        <select name='u-rtype' id='u-rtype'required>
                                            <option value=""></option>
                                            <option value="Standard">Standard</option>
                                            <option value="Deluxe">Deluxe</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Price:</label>
                                        <input name='u-price' id='u-price'required type='number'>
                                    </div>   
                                </div>
                                <button>Update</button>
                            </form>
                            <form id='ri-create-form' action='' method='post'>
                                <div>
                                    <div>
                                        <label>Type:</label>
                                        <input name='rtype' id='rtype'required>
                                    </div>
                                    <div>
                                        <label>Bedding:</label>
                                        <select name='desc' id='desc'required>
                                            <option value=""></option>
                                            <option value="Two Doubles">Two Doubles</option>
                                            <option value="Junior">Junior</option>
                                            <option value="Queen">Queen</option>
                                            <option value="King">King</option>
                                        </select>
                                    </div>  
                                    <div>
                                        <label>Price:</label>
                                        <input name='price' id='price'required type='number'>
                                    </div>  
                                </div>
                                <button>Create</button>
                            </form>
                        </div>
                    </div> <!-- display ri ends -->
                    <div class='display' id='display_ac'>
                        <form action='' method='post' id='ac-create-form'>
                            <div>
                                <label for='a-sid'>StaffID:</label>
                                <select name="a-sid" id="a-sid" required>
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div>
                                <label for='a-cid'>Course to assign:</label>
                                <select name="a-cid" id="a-cid" required>
                                    <option value="" ></option>
                                </select>
                            </div>
                            <button onclick="create('ac')">Assign</button>
                        </form>
                       
                    </div> <!-- display ac ends -->
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