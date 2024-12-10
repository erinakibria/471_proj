window.onload = async () => {

document.getElementById("si-update-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    update('si');
});

document.getElementById("si-create-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    create('si');
});

document.getElementById("si-delete-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    remove('si');
});

document.getElementById("di-update-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    update('di');
});

document.getElementById("di-create-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    create('di');
});

document.getElementById("di-delete-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    remove('di');
});

document.getElementById("ri-update-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    update('ri');
});

document.getElementById("ri-create-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    create('ri');
});

document.getElementById("ri-delete-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    remove('ri');
});

document.getElementById("ac-create-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    create('ac');
});
}

async function update(type){
    update_form = document.getElementById(type+"-update-form");
    form_data = new FormData(update_form);
    try{
        const response = await fetch("mActions.php", {
            method: "POST",
            body: form_data,
        });
        let result = await response.json();
        arr = Array.from(result);
        await displaySelection(type);  

    }catch(e){
        console.error(e);
    }
}

async function create(type){
    update_form = document.getElementById(type+"-create-form");
    form_data = new FormData(update_form);
    try{
        const response = await fetch("mActions.php", {
            method: "POST",
            body: form_data,
        });
        let result = await response.json();
        arr = Array.from(result);
        console.log(arr);
        
        await displaySelection(type);  

    }catch(e){
        console.error(e);
    }
}

async function remove(type){
    update_form = document.getElementById(type+"-delete-form");
    form_data = new FormData(update_form);
    try{
        const response = await fetch("mActions.php", {
            method: "POST",
            body: form_data,
        });
        let result = await response.json();
        arr = Array.from(result);
        console.log(arr);
        
        await displaySelection(type);  

    }catch(e){
        console.error(e);
    }
}

async function actionDisplay(formID){
    if(formID == "si-update-form"){
        document.getElementById("si-update-form").style.display = "block";
        document.getElementById("si-create-form").style.display = "none";
        document.getElementById("si-delete-form").style.display = "none"

    } 
    else if(formID == "si-create-form"){
        document.getElementById("si-update-form").style.display = "none";
        document.getElementById("si-delete-form").style.display = "none"

        document.getElementById("dep").innerHTML = "<option value=''></option>";
        try{
            const response = await fetch("mActions.php?getInfo=3", {
                method: "GET",
            });
            let result = await response.json();
            arr = Array.from(result);
            
            for(i=0; i<arr.length; i++){
                document.getElementById("dep").innerHTML += `
                <option value=${arr[i][0]}>${arr[i][1] +" ("+ arr[i][0]+")"}</option>
            `;
            }  
    
        }catch(e){
            console.error(e);
        }

        document.getElementById("si-create-form").style.display = "block"
    }
    else if(formID == "si-delete-form"){
        document.getElementById("si-update-form").style.display = "none";
        document.getElementById("si-create-form").style.display = "none";
        document.getElementById("si-delete-form").style.display = "block"
    }
    else if(formID == "di-update-form"){
        document.getElementById("di-update-form").style.display = "block";
        document.getElementById("di-create-form").style.display = "none";
        document.getElementById("di-delete-form").style.display = "none"

    } 
    else if(formID == "di-create-form"){
        document.getElementById("di-update-form").style.display = "none";
        document.getElementById("di-delete-form").style.display = "none"
        document.getElementById("di-create-form").style.display = "block"
    }
    else if(formID == "di-delete-form"){
        document.getElementById("di-update-form").style.display = "none";
        document.getElementById("di-create-form").style.display = "none";
        document.getElementById("di-delete-form").style.display = "block"
    }
}

async function displaySelection(selection){
    if(selection!='si-spec' && selection!='di-spec'){
        for(i=1; i<document.getElementById("middle").children.length; i++){
            if(document.getElementById("middle").children[i].id != ("display_"+selection)) document.getElementById("middle").children[i].style.display = 'none';
        }
    }
    // console.log(document.getElementById("middle").children);

    let label_elem = document.getElementById("label");
    switch(selection){
        case "si":{
            document.getElementById("si-update-form").style.display = "none";
            document.getElementById("si-create-form").style.display = "none";
            document.getElementById("si-delete-form").style.display = "none";

            table_elem = document.getElementById("si-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;
            document.getElementById("sid").innerHTML = "<option value=''></option>";
            document.getElementById("dep-sel").innerHTML = "<option value=''></option>";
            document.getElementById("u-id").value = "";
            document.getElementById("u-name").value = "";
            document.getElementById("u-phone").value = "";
            document.getElementById("u-mail").value = "";

            document.getElementById("dep").innerHTML = "<option value=''></option>";
            document.getElementById("fname").value = "";
            document.getElementById("pword").value = "";
            document.getElementById("lname").value = "";
            document.getElementById("phone").value = "";
            document.getElementById("mail").value = "";

            document.getElementById("d-sid").innerHTML = "<option value=''></option>";


            disp_elem = document.getElementById("display_si");
            label_elem.style.display = "none";
            try {
                const response = await fetch("mActions.php?getInfo=1", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    table_elem.innerHTML += `
                    <tr>
                        <td>${arr[i][0]}</td>
                        <td>${arr[i][1]}</td>
                        <td>${arr[i][3]}</td>
                        <td>${arr[i][4]}</td>
                        <td>${arr[i][2]}</td>
                    </tr>    
                    `;
                    
                    document.getElementById("sid").innerHTML += `
                        <option value=${arr[i][0]}>${arr[i][0]}</option>
                    `;
                    document.getElementById("d-sid").innerHTML += `
                    <option value=${arr[i][0]}>${arr[i][0]}</option>
                `;
                    
                }
            } catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
        case "si-spec":{
            sid = document.getElementById("sid").value;
            document.getElementById("dep-sel").innerHTML = "<option value=''></option>";
            document.getElementById("u-id").value = "";
            document.getElementById("u-name").value = "";
            document.getElementById("u-phone").value = "";
            document.getElementById("u-mail").value = "";
            try {
                const response = await fetch("mActions.php?getInfo=2&sid="+sid, {   
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?

                for(i=0; i< arr.length; i++){
                    if(i==0){
                        document.getElementById("u-id").value = arr[0][0];
                        document.getElementById("u-name").value = arr[0][1];
                        document.getElementById("u-phone").value = arr[0][2];
                        document.getElementById("u-mail").value = arr[0][3];
                    }
                    document.getElementById("dep-sel").innerHTML +=
                    `<option value='${arr[i][5]}'>${arr[i][4]+" ("+arr[i][5]+")"}</option>`;
                }

            } catch (e) {
              console.error(e);
            }
            break;
        }
        case "di":{
            document.getElementById("di-update-form").style.display = "none";
            document.getElementById("di-create-form").style.display = "none";
            document.getElementById("di-delete-form").style.display = "none";

            table_elem = document.getElementById("di-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;
            document.getElementById("did").innerHTML = "<option value=''></option>";
            // document.getElementById("dep-sel").innerHTML = "<option value=''></option>";
            document.getElementById("u-did").value = "";
            document.getElementById("u-dtype").value = "";
            document.getElementById("u-droles").value = "";

            // document.getElementById("dep").innerHTML = "<option value=''></option>";
            document.getElementById("type").value = "";
            document.getElementById("roles").value = "";

            document.getElementById("d-did").innerHTML = "<option value=''></option>";


            disp_elem = document.getElementById("display_di");
            label_elem.style.display = "none";
            try {
                const response = await fetch("mActions.php?getInfo=4", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    table_elem.innerHTML += `
                    <tr>
                        <td>${arr[i][0]}</td>
                        <td>${arr[i][1]}</td>
                        <td>${arr[i][2]}</td>
                        <td>${typeof(arr[i][3]) =='object'? 0:arr[i][3]}</td>
                    </tr>    
                    `;
                    
                    document.getElementById("did").innerHTML += `
                        <option value=${arr[i][0]}>${arr[i][0]}</option>
                    `;
                        document.getElementById("d-did").innerHTML += `
                        <option value=${arr[i][0]}>${arr[i][0]}</option>
                    `;
                      // only able to delete department with 0 employees
                    
                }
            } catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
        case "di-spec":{
            did = document.getElementById("did").value;
            document.getElementById("u-did").value = "";
            document.getElementById("u-dtype").value = "";
            document.getElementById("u-droles").value = "";
            try {
                const response = await fetch("mActions.php?getInfo=5&did="+did, {   
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?

                for(i=0; i< arr.length; i++){
                    
                    document.getElementById("u-did").value = arr[0][0];
                    document.getElementById("u-dtype").value = arr[0][1];
                    document.getElementById("u-droles").value = arr[0][2];
                

                }

            } catch (e) {
              console.error(e);
            }
            break;
        }
        case "ri":{
            document.getElementById("ri-update-form").style.display = "none";
            document.getElementById("ri-create-form").style.display = "none";
            document.getElementById("ri-delete-form").style.display = "none";

            table_elem = document.getElementById("ri-table");
            headers = table_elem.children[0].innerHTML;  // need to replace this first every time u clear
            table_elem.innerHTML = headers;
            document.getElementById("rid").innerHTML = "<option value=''></option>";
            // document.getElementById("dep-sel").innerHTML = "<option value=''></option>";
            document.getElementById("u-rid").value = "";
            document.getElementById("u-desc").value = "";
            document.getElementById("u-rtype").value = "";
            document.getElementById("u-price").value = "";

            // document.getElementById("dep").innerHTML = "<option value=''></option>";
            document.getElementById("rtype").value = "";
            document.getElementById("desc").value = "";
            document.getElementById("price").value = "";

            document.getElementById("d-rid").innerHTML = "<option value=''></option>";


            disp_elem = document.getElementById("display_ri");
            label_elem.style.display = "none";
            try {
                const response = await fetch("mActions.php?getInfo=6", {   // 1 indicates to retrieve all bookings, filters to come
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                // console.log(arr);
                // maybe add a display limit later/pagination ?
                for(i=0; i<arr.length; i++){
                    table_elem.innerHTML += `
                    <tr>
                        <td>${arr[i][0]}</td>
                        <td>${arr[i][1]}</td>
                        <td>${arr[i][2]}</td>
                        <td>${arr[i][3]}</td>
                    </tr>    
                    `;
                    
                    document.getElementById("rid").innerHTML += `
                        <option value=${arr[i][0]}>${arr[i][0]}</option>
                    `;
                        document.getElementById("d-rid").innerHTML += `
                        <option value=${arr[i][0]}>${arr[i][0]}</option>
                    `;
                      // only able to delete department with 0 employees
                    
                }
            } catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";
            break;
        }
        case "ri-spec":{
            rid = document.getElementById("rid").value;
            document.getElementById("u-rid").value = "";
            document.getElementById("u-rtype").value = "";
            document.getElementById("u-price").value = "";
            document.getElementById("u-desc").value = "";

            try {
                const response = await fetch("mActions.php?getInfo=7&rid="+rid, {   
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?

                for(i=0; i< arr.length; i++){
                    
                    document.getElementById("u-rid").value = arr[i][0];
                    document.getElementById("u-rtype").value = arr[i][1];
                    document.getElementById("u-price").value = arr[i][2];
                    const regex = /, (.)+/i;
                    document.getElementById("u-desc").value = arr[i][3].replace(regex, '');
                
                }

            } catch (e) {
              console.error(e);
            }
            break;
        }
        case "ac":{
            label_elem.style.display = "none";
            disp_elem = document.getElementById("display_ac");
            document.getElementById("a-sid").innerHTML = "<option value=''></option>";
            // disp_elem = document.getElementById("");
            document.getElementById("a-cid").innerHTML = "<option value=''></option>";
            try {
                const response = await fetch("mActions.php?getInfo=8", {   
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?

                for(i=0; i< arr.length; i++){
                    document.getElementById("a-sid").innerHTML += 
                    `<option value=${arr[i][0]}>${arr[i][0]}</option>`;
                
                }

            } catch (e) {
              console.error(e);
            }
            try {
                const response = await fetch("mActions.php?getInfo=9", {   
                  method: "GET",
                });
                let result = await response.json();
                arr = Array.from(result);
                console.log(arr);
                // maybe add a display limit later/pagination ?

                for(i=0; i< arr.length; i++){
                    document.getElementById("a-cid").innerHTML += 
                    `<option value=${arr[i][0]}>${arr[i][0]}</option>`;
                
                }

            } catch (e) {
              console.error(e);
            }
            disp_elem.style.display = "inline";

        }
    }
}

