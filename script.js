
function enableInputFields(MeasurementID) {
    // get the name and contact input fields
    var input = document.getElementById("name" + MeasurementID);
    var editbtn = document.getElementById('editbtn' + MeasurementID);
    var save = document.getElementById('save' + MeasurementID)
    var cancel = document.getElementById('cancel' + MeasurementID)

    var inputs = document.querySelectorAll(".all" + MeasurementID);
    if (inputs) {
        inputs.forEach(input => {
            input.style.border = "1px solid #000";
            input.disabled = false;
        });
    }


    editbtn.style.display = 'none';
    cancel.style.display = 'inline';
    save.style.display = 'inline';
}

function myFunction(MeasurementID) {
    var input = document.getElementById("name" + MeasurementID);
    input.required = false;

}

