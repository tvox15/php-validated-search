//// JS DATA VALIDATION ////
document.getElementById("submit").addEventListener("click", function(event) {
    // get zip code field value
    var zipcodeField = document.getElementById("zipcode-field").value;

    // Create regex to only allow numbers to be submitted
    var reg = new RegExp('^\\d+$');

    // ERROR CHECK 1 // check to verify zip code field is not empty
    if (zipcodeField === '') {
        //set error message
        document.getElementById("error").innerHTML = "You must enter a zip code";
        // prevent POST 
        event.preventDefault()
    }

    // ERROR CHECK 2 // check to verify zip code is between 3 and 5 characters
    else if (zipcodeField.length < 3 || zipcodeField.length > 5) {
        //set error message
        document.getElementById("error").innerHTML = "Invalid Zip Code";
        // prevent POST 
        event.preventDefault()
    }

    // ERROR CHECK 3 // check to verify only numbers are in the zip code field
    else if (!reg.test(zipcodeField)) {
        //set error message
        document.getElementById("error").innerHTML = "Not A Zip Code";
        // prevent POST 
        event.preventDefault()
    }
});