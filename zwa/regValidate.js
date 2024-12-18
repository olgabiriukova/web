

function valid(){
    document.getElementById('regForm').addEventListener('submit', function (event) {

        event.preventDefault();
        //if (validate()) {
            submitAjax();
        //} else {
            //console.log('not valid');
        //}
        });

}

function validate() {
    var name = document.getElementById("name").value;
    var surname = document.getElementById("surname").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var repeat = document.getElementById("repeat").value;

    var role = document.querySelector('input[name="role"]:checked');
    if (!role) {
        alert("Please select a role.");
        return false;
    }
    role = role.value;

    var avatar = document.getElementById("avatar");
    if (avatar.files.length === 0) {
        alert("Please choose an avatar.");
        return false;
    }

    if (name === "" || surname === "" || email === "" || password === "" || repeat === "") {
        alert("All fields must be filled.");
        return false;
    }

    if (password !== repeat) {
        alert("Passwords do not match.");
        return false;
    }
    var allowedFormats = ["jpg", "jpeg", "png"];
    var fileExtension = avatar.value.split('.').pop().toLowerCase();
    if (allowedFormats.indexOf(fileExtension) === -1) {
        alert("Invalid file format. Allowed formats: " + allowedFormats.join(", "));
        return false;
    }

    return true;
}

function submitAjax() {
    if (validate()) {
        var form = document.getElementById("regForm");
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "reg.php", true);
        

        xhr.onreadystatechange = function() {
            
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                   
                    
                    var response = JSON.parse(xhr.responseText);
                   
                    if (response.success) {
                        
                        alert("Registration successful");
                        window.location.href = "account.php";
                    } else {
                        if (response.message === "Email is already registered") {
                            alert("Email is already registered.");
                        } else {
                            alert("Error: " + response.message);
                        }
                    }
                } else {
                    console.error("Error: " + xhr.statusText);
                }
            }
        };

        xhr.send(formData);
    }
}
    


          

    