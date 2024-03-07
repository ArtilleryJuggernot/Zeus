function enableDate(){
    let radio_is_due = document.getElementById("is_due").checked
    let dt_input = document.getElementById("dt_input").disabled = !radio_is_due;
}

document.getElementById("is_due").addEventListener("click" ,() => enableDate());
