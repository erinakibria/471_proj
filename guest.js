let rid_to_book = -1;   // saves last rid clicked by guest before submitting
let amount = -1;
let numDays = -1;
let globalRR;

window.onload = async () => {

    document.getElementById("rc-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("book-form").addEventListener("submit", async (event) => {
        event.preventDefault();
        confirm(1);
    });

    document.getElementById("br-filter-form").addEventListener("submit", async (event) => {
        event.preventDefault();
        applyFilters();
    });

    
    async function renderRooms(){
        rooms_elem = document.getElementById("display-rooms");
        rooms_elem.innerHTML = "";
        try {
            const response = await fetch("gActions.php?getInfo=1", {   // 1 indicates to retrieve all bookings, filters to come
              method: "GET",
            });
            let result = await response.json();
            arr = Array.from(result);
            // console.log(arr);
            // maybe add a display limit later/pagination ?
            const regex = /, (.)+/i;
            for(i=0; i<arr.length; i++){
                // for(j=0; j<7; j++){
                    rooms_elem.innerHTML += `
                    <div class='room' id='${arr[i][0]}' onclick='bookReq(this.id)'>
                        <img src='rooms/room1.jpeg'>
                        <div class='room-lab'>
                            <p>Room description: </p>
                            <p>Type: </p>
                            <p style="display: none;">Next availible check-in: </p>
                        </div>
                        <div class='room-info'>
                            <p>${arr[i][1].replace(regex, '')}</p>
                            <p>${arr[i][3]}</p>
                            <p style="display: none;">${typeof(arr[i][5])=='object'?'Today':arr[i][5]}</p>
                        </div>
                        <div class='room-price'>
                            <p id="p${arr[i][0]}" style="display: none;">${arr[i][4]}</p>
                            <p>$${arr[i][4]} CAD</p>
                        </div>
                    </div>`;

            }
        } catch (e) {
          console.error(e);
        }
    }

    await renderRooms();

    globalRR = renderRooms;
}

async function applyFilters(){
    filter_form = new FormData(document.getElementById("br-filter-form"));

    try{
        const response = await fetch("gActions.php", {
            method: "POST",
            body: filter_form,
        });
        let result = await response.json();
        arr = Array.from(result);
        // console.log(arr);
        const regex = /, (.)+/i;
        // console.log(paragraph.replace(regex, ''));
        if(arr.length > 0){
            rooms_elem = document.getElementById("display-rooms");
            rooms_elem.innerHTML = "";
            for(i=0; i < arr.length; i++){
                rooms_elem.innerHTML += `
                <div class='room' id='${arr[i][0]}' onclick='bookReq(this.id)'>
                    <img src='rooms/room1.jpeg'>
                    <div class='room-lab'>
                        <p>Room description: </p>
                        <p>Type: </p>
                    </div>
                    <div class='room-info'>
                        <p>${arr[i][1].replace(regex, '')}</p>
                        <p>${arr[i][2]}</p>
                    </div>
                    <div class='room-price'>
                        <p id="p${arr[i][0]}" style="display: none;">${arr[i][3]}</p>
                        <p>$${arr[i][3]} CAD</p>
                    </div>
                </div>`;
            }
        }
        else{
            alert("No vacancies matching these filters!");
        }
        
    }catch(e){
        console.error(e);
    }
}

async function showReciept(){
    form_data = new FormData(document.getElementById("rc-form"));
    try{
        const response = await fetch("gActions.php", {
            method: "POST",
            body: form_data,
        });
        let result = await response.json();
        arr = Array.from(result);
        console.log(arr);

        document.getElementById("receipt").style.display = "flex";
        document.getElementById("receipt").innerHTML = `
            <h1 style="text-decoration: underline;">Hotel System</h1>
            <br>
            <br>
            <p>Booking ID: ${arr[0]} </p>
            <p>Check-in: ${arr[1]}</p>
            <p>Check-out: ${arr[2]}</p>
            <br>
            <p>Invoice ID: ${arr[3]} </p>
            <p>Total: $${arr[5]} CAD</p>
            <p>Date paid: ${arr[4]} </p>
            <p>Card: ${arr[6]} </p>
            <p>Hope you enjoyed your stay!</p>
        `;
        
    }catch(e){
        console.error(e);
    }
}

function bookReq(rid){
    if(numDays == -1){
        if (rid_to_book != -1) document.getElementById(rid_to_book).style.backgroundColor = "white";
        rid_to_book = rid;
        // document.getElementById("cont-msg").innerText = "Booking room " + rid+":";
        try{
            document.getElementById("guest-panel").style.display = "none";
            document.getElementById("top-login").style.display = "none";
        }catch(e){};
        document.getElementById("booking-form").style.display = "inline";
        document.getElementById(rid).style.backgroundColor = "lightpink";
    }
}


function showFilters(type){
    if(document.getElementById("filter-panel-"+type).style.display == "block") document.getElementById("filter-panel-"+type).style.display = "none";
    else document.getElementById("filter-panel-"+type).style.display = "block";
}

async function login(type){
    switch(type){
        case 1:{
            form_data = new FormData(document.getElementById("have-form1"));
            try {
                const response = await fetch("gActions.php?getInfo=2", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "POST",
                  body: form_data,
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);

                document.getElementById("welcome-msg").innerText = "Dashboard for "+ arr[0];
                document.getElementById("guest-panel").style.display = "flex";
                
            } catch (e) {
              console.error(e);
            }
        }
    }
}

async function displaySelection(sid){
    document.getElementById("stat-div").style.display = "none";
    document.getElementById("room-div").style.display = "none";
    document.getElementById("conf-div").style.display = "none";
    document.getElementById("reciept-div").style.display = "none";

    switch(sid){
        case "reciept":{
            select_elem = document.getElementById("rc-select");
            select_elem.innerHTML = "<option value=''></option>";
            document.getElementById("receipt").innerHTML = "";
            document.getElementById("receipt").style.display = "none";
            try {
                const response = await fetch("gActions.php?getInfo=2", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);

                for(i=0; i<arr.length; i++){
                    select_elem.innerHTML += `<option value='${arr[i][0]}'>${arr[i][0]}</option>`;
                }
                
            } catch (e) {
              console.error(e);
            }
            break;
        }
        case "conf":{
            document.getElementById("conf-table").innerHTML = 
            ` 
                <tr>
                    <th>Booking ID</th>
                    <th>Room ID</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                </tr>`;
            try {
                const response = await fetch("gActions.php?getInfo=4", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);

                for(i=0; i<arr.length; i++){
                    if(arr[i][4] > 0) stat='Incomplete';
                    else if(arr[i][5] <= 0) stat='Checked-out';
                    else stat = 'Checked-in';
                    document.getElementById("conf-table").innerHTML += 
                    `<td>${arr[i][0]}</td>
                    <td>${arr[i][3]}</td>
                    <td>${arr[i][1]}</td>
                    <td>${arr[i][2]}</td>
                    <td>${stat}</td>`;
                }
                
                
            } catch (e) {
              console.error(e);
            }
            break;
        }
        case "room":{
            await globalRR();
            break;
        }
        case "stat":{
            let a = false;
            document.getElementById("stat-table").innerHTML = 
            ` 
                <tr>
                    <th>Room ID</th>
                    <th>Housekeeping status</th>
                </tr>`;
            try {
                const response = await fetch("gActions.php?getInfo=5", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                const regex = /(.)+, /i;
                // console.log(paragraph.replace(regex, ''));
                for(i=0; i<arr.length; i++){
                    document.getElementById("stat-table").innerHTML += 
                    `<td>${arr[i][0]}</td>
                    <td>${arr[i][1].replace(regex, '')}</td>`;
                    if(arr[i][1].replace(regex, '') == "Housekeeping in progress") a = true;
                }

                if(a) alert("Housekeeping is in progress in at least one of your rooms");
                
                
            } catch (e) {
              console.error(e);
            }
            break;
        }

    }
    
    if(sid=='room') document.getElementById(sid+"-div").style.display = "flex";
    else document.getElementById(sid+"-div").style.display = "inline";
}

async function confirm(step){
    console.log(rid_to_book);
    switch(step){
        case 1:{
            form_data = new FormData(document.getElementById("book-form"));
            cid = new Date(form_data.get('ci-date') + 'T00:00:00');
            cod = new Date(form_data.get('co-date') + 'T00:00:00');
            // check for check in to be less than check out, also booking for 1 night min
            try {
                const response = await fetch("gActions.php?getInfo=3&rid="+rid_to_book, {   // 1 indicates to retrieve all bookings, filters to come
                  method: "POST",
                  body: form_data,
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(form_data.get('ci-date'));
        

                if(((cod.valueOf() - cid.valueOf()) / (24*60*60*1000)) <= 0){
                    alert("Invalid dates. A minimum of one night is required.");
                }
                else if(arr.length > 0){
                    alert("Dates conflict with existing booking(s). Filter rooms by date to see other options.");
                }
                else{
                    numDays = ((cod.valueOf() - cid.valueOf()) / (24*60*60*1000));
                    amount = parseFloat(document.getElementById("p"+rid_to_book).innerText);

                    conf_elem = document.getElementById("book-form").style.display = "none";
                    document.getElementById("booking-conf").innerHTML = `
                    <p>Confirm details: </p>
                    <form id='final-conf-form'>
                        <div>
                            <label>RoomID: </label>
                            <input readonly id='conf-id' name='final-rid' value="${rid_to_book}"></input>
                        </div>
                        <div>
                            <label>Check-in: </label>
                            <input readonly id='conf-id' name='final-cid' value='${form_data.get('ci-date')}'></input>
                        </div>
                        <div>
                            <label>Check-out: </label>
                            <input readonly id='conf-id' name='final-cod' value='${form_data.get('co-date')}'></input>
                        </div>
                        <div>
                            <label>Amount: </label>
                            <input readonly id='conf-amt' name='final-amt' value='${(amount*numDays)}'></input>
                        </div>
                        <div>
                            <label>Payment: </label>
                            <select id='conf-pay' required onchange="renderPay()" name='final-pay'> 
                                <option value=''></option>
                                <option value='Oco'>On check-out</option>
                                <option value='Now'>Now</option>
                            </select>
                        </div>
                        <div id='pay-now' style='display: none;'>
                            <label>Card number: </label>
                            <input id='cno' name='final-cno' type='number' min=1 max=9999999999999999></input>
                        </div>
                        <button>Book</button>
                    </form>
                    `;

                    document.getElementById("final-conf-form").addEventListener("submit", async (event) => {
                        event.preventDefault();
                        await finalBook();
                        rid_to_book, amount, numDays = -1;
                        document.getElementById("booking-conf").innerHTML = '';
                        document.getElementById("guest-panel").style.display = "flex";
                        displaySelection('conf');
                    });
                }
                
            } catch (e) {
              console.error(e);
            }
            break;
        }

    }
}

function renderPay(){
    if(document.getElementById("conf-pay").value == 'Now'){
        document.getElementById("pay-now").style.display = "block";
        document.getElementById("cno").required = true;

    }
    else{ 
        document.getElementById("pay-now").style.display = "none";
        document.getElementById("cno").required = false;
    }
}

async function finalBook(){
    form_data_final = new FormData(document.getElementById("final-conf-form"));
    try {
        const response = await fetch("gActions.php?", {   // 1 indicates to retrieve all bookings, filters to come
          method: "POST",
          body: form_data_final,
        });
        let result = await response.json();
        arr = Array.from(result);
        console.log(arr);
        // document.getElementById("book-success").innerText = "Success! Your BookingID is: " + arr[0];
        
    } catch (e) {
      console.error(e);
    }
}

function back(){
    rid_to_book, amount, numDays = -1;
    // document.getElementById("booking-conf").innerHTML = '';
    document.getElementById("booking-form").style.display = 'none';
    document.getElementById("guest-panel").style.display = "flex";
}
