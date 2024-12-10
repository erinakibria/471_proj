<?php
    session_unset();
    session_start();

    include 'hotel_db.php';
    if(isset($_POST['sid']) && (isset($_POST['pword']))){
        $sid = $_POST['sid'];
        $pword = $_POST['pword'];
        $sql = "SELECT * FROM Staff WHERE StaffID='$sid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $records = $result->fetch_row();
            if($records[1] == $pword){
                    $_SESSION['sid'] = $sid;
                    header("Location: staff.php");
                    die();
            }else{
                $_SESSION['fail'] = true;
            }
        }else{
            $_SESSION['fail'] = true;
        }
    }
    else if(isset($_POST['mid']) && (isset($_POST['pword']))){
        $mid = $_POST['mid'];
        $pword = $_POST['pword'];
        $sql = "select ManagerID from Management inner join Staff on Management.StaffID = Staff.StaffID where ManagerID='$mid' and Staff.Password = '$pword'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $records = $result->fetch_row();
            $_SESSION['mid'] = $mid;
            echo "yea";
            header("Location: management.php");
            die();

        }else{
            $_SESSION['fail'] = true;
        }
    }
    else if(isset($_POST['gid']) && (isset($_POST['pword']))){
        $gid = $_POST['gid'];
        $pword = $_POST['pword'];
        $sql = "SELECT * FROM Guest WHERE GuestID='$gid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $records = $result->fetch_row();
            if($records[1] == $pword){
                    $_SESSION['gid'] = $gid;
                    echo "yea";
                    header("Location: guest.php");
                    die();
            }else{
                $_SESSION['fail'] = true;
            }
        }else{
            $_SESSION['fail'] = true;
        }
    }
    else if(isset($_POST['gfname']) && (isset($_POST['pword']))){
        $pword = $_POST['pword'];
        $gname = $_POST['gfname'] . " " .$_POST['glname'];
        $gphone = $_POST['gphone'];
        $sql = "insert into Guest (Password, Name, Phone) values ('$pword', '$gname', '$gphone')";
        $conn->query($sql);
        $sql = "select last_insert_id()";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $records = $result->fetch_row();
            if($records[0]){
                    $_SESSION['gid'] = $records[0];
                    echo "yea";
                    header("Location: guest.php");
                    die();
            }else{
                $_SESSION['fail'] = true;
            }
        }else{
            $_SESSION['fail'] = true;
        }
    }
?>
<DOCTYPE! html>
    <html>
        <head>
            <style>
                .intro, .login{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    font-family: sans-serif;
                    font-size: 17px
                }

                .role-div{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    margin-bottom: 50px;
                    gap: 10px;
                }

                .stafflogin{
                    display: flex;
                    width: 60%;
                    align-items: center;
                    justify-content: center;
                }

                #sid_form, #mid_form, #gid_form, #newgid_form{
                    display: none;
                    flex-direction: column;
                    gap: 20px;
                    align-items: center;
                }

                #gid_form{
                    display: flex;
                }

                #sid_form button{
                    width: 50%;
                }

                .err-msg{
                    color: red;
                }
                
            </style>
            <script src='staff.js'></script>
        </head>
        <body>
            <div class='intro'>
                <h1>Welcome to our Hotel Management System!</h1>
                <h2>Please select your role to continue:</h2>
                <div class='role-div'>
                    <button id='newguest' onclick="renderForm(this.id)">New guest</button>
                    <button id='guest' onclick="renderForm(this.id)">Returning guest</button>
                    <button id='staff' onclick="renderForm(this.id)">Staff</button>
                    <button id='management' onclick="renderForm(this.id)">Management</button> 
                </div>
                <div class='login'>
                    <form action='' method='post' id='sid_form'>
                        <div>
                            <label for='sid'>Staff ID:</label>
                            <input required type='text' name='sid'></input>
                        </div>
                        <div>
                            <label for='pword'>Password:</label>
                            <input required type='password' name='pword'></input>
                        </div>
                        <button type='submit'>Continue as staff</button>
                    </form>
                    <form action='' method='post' id='mid_form'>
                        <div>
                            <label for='mid'>Manager ID:</label>
                            <input required type='text' name='mid'></input>
                        </div>
                        <div>
                            <label for='pword'>Password:</label>
                            <input required type='password' name='pword'></input>
                        </div>
                        <button type='submit'>Continue as manager</button>
                    </form>
                    <form action='' method='post' id='gid_form'>
                        <div>
                            <label for='sid'>Guest ID:</label>
                            <input required type='text' name='gid'></input>
                        </div>
                        <div>
                            <label for='pword'>Password:</label>
                            <input required type='password' name='pword'></input>
                        </div>
                        <button type='submit'>Continue as guest</button>
                    </form>
                    <form action='' method='post' id='newgid_form'>
                        <div>
                            <label for='gfname'>First name:</label>
                            <input required type='text' name='gfname'></input>
                        </div>
                        <div>
                            <label for='glname'>Last name:</label>
                            <input required type='text' name='glname'></input>
                        </div>
                        <div>
                            <label for='gphone'>Phone number:</label>
                            <input required type='number' name='gphone'></input>
                        </div>
                        <div>
                            <label for='pword'>Password:</label>
                            <input required type='password' name='pword'></input>
                        </div>
                        <button type='submit'>Create account</button>
                    </form>
                    <?php
                        if(isset($_SESSION['fail'])){
                            if($_SESSION['fail']) echo "<p class='err-msg'>Invalid UserID/Password</p><br>";
                        }
                        session_unset();

                    ?>
                </div>
            </div>
        </body>
    </html>