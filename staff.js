window.onload = async () => {

    document.getElementById("vb-filter-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("vv-filter-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("vv-update-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("pci-update-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("pco-update-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("hk-update-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });

    document.getElementById("iv-filter-form").addEventListener("submit", async (event) => {
        event.preventDefault();
    });


}

function displayCourse(){
    if(document.getElementById("cid").value != ''){
        document.getElementById("course-content").innerText = "Course content for: " + document.getElementById("cid").value;
        document.getElementById("hidden-logid").innerText = document.getElementById("cid").value;
        document.getElementById("course-finish").style.display = "inline";
    }
    else{
        document.getElementById("course-content").innerText = "";
        document.getElementById("hidden-logid").innerText = "";
        document.getElementById("course-finish").style.display = "none";



    }
}

async function update(type){
    if(type=='pco'){
        tid = document.getElementById("t-id").innerText;
        cardno = document.getElementById("t-card").value;
        if(tid != ''){
            try{
                const response = await fetch("actions.php?tid="+tid+"&cno="+cardno, {
                    method: "POST",
                });
                await response.json();
                document.getElementById("pco-success").innerText = "Success! Invoices will be availible.";      
        
            }catch(e){
                console.error(e);
            }
        }
    }
    else if(type=='cc'){
        logid = document.getElementById("hidden-logid").innerText;
        try{
            const response = await fetch("actions.php?lc="+logid, {
                method: "POST",
            });
            await response.json();
            await displaySelection(type);      
    
        }catch(e){
            console.error(e);
        }
    }
    else{
        update_form = document.getElementById(type+"-update-form");
        form_data = new FormData(update_form);
        try{
            const response = await fetch("actions.php", {
                method: "POST",
                body: form_data,
            });
            let result = await response.json();
            arr = Array.from(result);
            if(type=="vv" || type=='hk') await displaySelection(type);  
            else if(type=="pci") document.getElementById("pci-success").innerText = "Success!";      
    
        }catch(e){
            console.error(e);
        }
    }

}

async function applyFilters(type, numAttr){
    filter_form = document.getElementById(type+"-filter-form");
    form_data = new FormData(filter_form);
    try{
        const response = await fetch("actions.php", {
            method: "POST",
            body: form_data,
        });
        let result = await response.json();
        arr = Array.from(result);
        // console.log(arr);
        
        table_elem = document.getElementById(type+"-table");
        headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
        table_elem.innerHTML = headers;
        // console.log(table_elem.children);
        for(i=0; i < arr.length; i++){
            row = document.createElement("tr");
            for(j=0; j<numAttr; j++){
                if(j==6 && type=='vb'){
                    if(arr[i][7] <= 0) info = document.createTextNode("Complete");
                    else if(arr[i][6] <= 0) info = document.createTextNode("In progress");
                    else info = document.createTextNode("Incomplete");
                    entry = document.createElement("td");
                    entry.append(info);
                    row.append(entry);
                }
                else{
                    info = document.createTextNode(arr[i][j]);
                    entry = document.createElement("td");
                    entry.append(info);
                    row.append(entry);
                }
            }
            table_elem.append(row);
        }
        
    }catch(e){
        console.error(e);
    }
  }

function showFilters(type){
    if(document.getElementById("filter-panel-"+type).style.display == "block") document.getElementById("filter-panel-"+type).style.display = "none";
    else document.getElementById("filter-panel-"+type).style.display = "block";
}



function renderForm(type){
    let staff_form = document.getElementById("sid_form");
    let man_form = document.getElementById("mid_form");
    let g_form = document.getElementById("gid_form");
    let new_g_form = document.getElementById("newgid_form");

    switch(type){
        case "staff":{
            man_form.style.display = "none";
            g_form.style.display = "none";
            new_g_form.style.display = "none";
            staff_form.style.display = "flex";
            break;
        }
        case "management":{
            man_form.style.display = "flex";
            staff_form.style.display = "none";
            new_g_form.style.display = "none";
            g_form.style.display = "none";
            break;
        }
        case "guest":{
            man_form.style.display = "none";
            g_form.style.display = "flex";
            new_g_form.style.display = "none";
            staff_form.style.display = "none";
            break;
        }
        case "newguest":{
            man_form.style.display = "none";
            g_form.style.display = "none";
            new_g_form.style.display = "flex";
            staff_form.style.display = "none";
            break;
        }
        
    }
}

async function displaySelection(selection){
    for(i=1; i<document.getElementById("middle").children.length; i++){
        if(document.getElementById("middle").children[i].id != ("display_"+selection)) document.getElementById("middle").children[i].style.display = 'none';
    }

    let label_elem = document.getElementById("label");
    switch(selection){
        case "vb":{
            table_elem = document.getElementById("vb-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;

            disp_elem = document.getElementById("display_vb");
            label_elem.style.display = "none";
            try {
                const response = await fetch("actions.php?getInfo=1", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    row = document.createElement("tr");
                    for(j=0; j<7; j++){
                        if(j==6){
                            if(arr[i][7] <= 0) info = document.createTextNode("Complete");
                            else if(arr[i][6] <= 0) info = document.createTextNode("In progress");
                            else info = document.createTextNode("Incomplete");
                        }
                        else{
                            info = document.createTextNode(arr[i][j]);
                            // console.log(arr[i][j]);
                        }
                        entry = document.createElement("td");
                        entry.append(info);
                        row.append(entry);
                    }
                    disp_elem.children[1].append(row);
                }
            } catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
        case "vv":{
            table_elem = document.getElementById("vv-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;
            document.getElementById("r-update").innerHTML = "<option value=''></option>";   // clear options

            disp_elem = document.getElementById("display_vv");
            label_elem.style.display = "none";
            try {
                const response = await fetch("actions.php?getInfo=2", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    row = document.createElement("tr");
                    for(j=0; j<5; j++){
                        info = document.createTextNode(arr[i][j]);
                        if(j==0) {
                            option = document.createElement("option");  // this is to populate the update section
                            option.value = arr[i][j];
                            option.innerText = arr[i][j];
                            document.getElementById("r-update").append(option);
                        }
                        // console.log(arr[i][j]);
                        entry = document.createElement("td");
                        entry.append(info);
                        row.append(entry);
                    }
                    disp_elem.children[1].append(row);
                }
                }
            catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";


            break;
        }
        case "pci":{
            disp_elem = document.getElementById("display_pci");
            label_elem.style.display = "none";
            document.getElementById("pci-update").innerHTML = "<option value=''></option>";   // clear options

            try {
                const response = await fetch("actions.php?getInfo=3", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    info = document.createTextNode(arr[i][0]);
                    option = document.createElement("option");  // this is to populate the update section
                    option.value = arr[i][0];
                    option.innerText = arr[i][0];
                    document.getElementById("pci-update").append(option);
                }
                }
            catch (e) {
              console.error(e);
            }

            disp_elem.style.display = 'inline';
            break;
        }
        case "pco-conf":{
            bid = document.getElementById("pco-update").value;
            if(bid != ''){
                try {
                    const response = await fetch("actions.php?pco-bid="+bid, {   // 1 indicates to retrieve all bookings (initial)
                      method: "GET",
                    });
                    let result = await response.json();
                    arr = Array.from(result);
                    console.log(arr);
                    document.getElementById("t-id").innerText = arr[0];
                    document.getElementById("t-amt").innerText = arr[1];
                    document.getElementById("t-guest").innerText = arr[2];

                    }
                catch (e) {
                  console.error(e);
                }
            }
        }
        case "pco":{
            disp_elem = document.getElementById("display_pco");
            label_elem.style.display = "none";
            document.getElementById("pco-update").innerHTML = "<option value=''></option>";   // clear options

            try {
                const response = await fetch("actions.php?getInfo=4", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    info = document.createTextNode(arr[i][0]);
                    option = document.createElement("option");  // this is to populate the update section
                    option.value = arr[i][0];
                    option.innerText = arr[i][0];
                    document.getElementById("pco-update").append(option);
                }
                }
            catch (e) {
              console.error(e);
            }

            disp_elem.style.display = 'inline';
            break;
        }
        case "cc":{
            label_elem.style.display = "none";
            disp_elem = document.getElementById("display_cc");
            table_elem = document.getElementById("course-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;
            document.getElementById("cid").innerHTML = "<option value=''></option>";   // clear options

            try {
                const response = await fetch("actions.php?getInfo=5", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?
                    for(i=0; i<arr.length; i++){
                        row = document.createElement("tr");
                        for(j=0; j<5; j++){
                            info = document.createTextNode(arr[i][j]);
                            if(j==0 && arr[i][4] == 'Incomplete') {
                                option = document.createElement("option");  // this is to populate the update section
                                option.value = arr[i][5]; //log id
                                option.innerText = arr[i][1] + "(" + arr[i][5]+")";
                                document.getElementById("cid").append(option);
                            }
                            // console.log(arr[i][j]);
                            entry = document.createElement("td");
                            entry.append(info);
                            row.append(entry);
                        }
                        disp_elem.children[0].append(row);
                    }
                }
            catch (e) {
              console.error(e);
            }
            disp_elem.style.display = 'inline';
            break;
        }
        case 'hk':{
            label_elem.style.display = "none";
            disp_elem = document.getElementById("display_hk");
            table_elem = document.getElementById("hk-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;

            try {
                const response = await fetch("actions.php?getInfo=6", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // if(arr.length == 0){    // not hk employee
                //     alert("This is only viewable to housekeeping employees");
                // }
                // else{
                    disp_elem.style.display = "inline";
                    try {
                        const response = await fetch("actions.php?getInfo=7", {   // 1 indicates to retrieve all bookings (initial)
                          method: "GET",
                        });
                        let result = await response.json();
                        arr = Array.from(result);
                        console.log(arr);
                        // maybe add a display limit later/pagination ?
                            for(i=0; i<arr.length; i++){
                                row = document.createElement("tr");
                                for(j=0; j<5; j++){
                                    info = document.createTextNode(arr[i][j]);
                                    if(j==0) {
                                        option = document.createElement("option");  // this is to populate the update section
                                        option.value = arr[i][j]; //log id
                                        option.innerText = arr[i][j];
                                        document.getElementById("hkid").append(option);
                                    }
                                    // console.log(arr[i][j]);
                                    entry = document.createElement("td");
                                    entry.append(info);
                                    row.append(entry);
                                }
                                disp_elem.children[0].append(row);
                            }
                        }
                    catch (e) {
                      console.error(e);
                    }
                // }
                
                }
            catch (e) {
              console.error(e);
            }
            break;
        }
        case 'iv':{
            label_elem.style.display = "none";
            disp_elem = document.getElementById("display_iv");
            table_elem = document.getElementById("iv-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;

            try {
                const response = await fetch("actions.php?getInfo=9", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?
                    for(i=0; i<arr.length; i++){
                        row = document.createElement("tr");
                        for(j=0; j<5; j++){
                            info = document.createTextNode(arr[i][j]);
                            // console.log(arr[i][j]);
                            entry = document.createElement("td");
                            entry.append(info);
                            row.append(entry);
                        }
                        disp_elem.children[1].append(row);
                    }
                }
            catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
        case 'help':{
            label_elem.style.display = "none";
            disp_elem = document.getElementById("display_help");
            table_elem = document.getElementById("help-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;

            try {
                const response = await fetch("actions.php?getInfo=8", {   // 1 indicates to retrieve all bookings (initial)
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?
                    for(i=0; i<arr.length; i++){
                        row = document.createElement("tr");
                        for(j=0; j<3; j++){
                            info = document.createTextNode(arr[i][j]);
                            // console.log(arr[i][j]);
                            entry = document.createElement("td");
                            entry.append(info);
                            row.append(entry);
                        }
                        disp_elem.children[0].append(row);
                    }
                }
            catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
    }
}
