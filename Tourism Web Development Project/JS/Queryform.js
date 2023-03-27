// getting query page innerHTML and assigning it to variable(details)
var elem = document.getElementById("wrap-form");
var details = elem.innerHTML;

// variables to store query fields
var name = "";
var email = "";
var query = "";
var select ="";

function viewQuery(){
    // getting values from fields
    Name = document.form.name.value;
    email = document.form.email.value;
    query = document.form.message.value;
    select = document.form.select.value;
    subject = document.form.subject.value;
    

    if(checkName(Name.trim())){
        alert("Name is invalid");
    } else if(query.trim() == ""){
        alert("Query is invalid");
    }  else if (checkEmail(email.trim())) {
        alert("Email is invalid");
    } else if (select.trim()=="") {
        alert("select your idea");
    }  else if(subject.trim() == ""){
        alert("Please select yes or no");
    }

    else {
        // Showing the results & two buttons
        elem.innerHTML = `<p> Name: ${Name} <br><br><br> Email: ${email} <br><br><br> Comments: ${query}<br><br><br> How is our Packages: ${select} <br><br><br> Will you suggest our website to your freinds: ${subject} <br><br><br> </p>`;
       
        var send_btn = document.createElement("BUTTON");
        send_btn.innerHTML = "Send";        
        send_btn.classList.add("send_btn");

        var edit_btn = document.createElement("BUTTON");
        edit_btn.innerHTML = "Edit";
        edit_btn.classList.add("edit_btn");

        elem.appendChild(send_btn);
        elem.appendChild(edit_btn);

        // when the edit button is clicked we showing the previous page with filled in results
        edit_btn.addEventListener("click", function() {
            elem.innerHTML = details;
            document.form.name.value = Name;
            document.form.email.value = email;
            document.form.message.value = query;
            document.form.select.value = select;
            document.form.subject.value = subject;
        });   

        //sending our details using gmail
        send_btn.addEventListener("click", function() {
            elem.innerHTML = details;  
            alert("Sending email to " + email); 
            let mail_query = `mailto:${email}?subject=Query&body=Name:${Name}%0D%0AEmail:${email}%0D%0AHow is our pakages:${select}%0D%0AComments:${query}%0D%0AWill you suggest our website to your friends:${subject}`;
            window.open(mail_query,'_blank');
        });                  
    }
}

// function to validate email using regular expression
// function returns true if email is in valid format
function checkEmail(email) {
    let pattern =/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return !pattern.test(email);
}

// function to validate name using regular expression
// function returns true if name is in correct format
function checkName(name) {
    let pattern = /^[ a-zA-Z\-\’]+$/;
    return !pattern.test(name);
}