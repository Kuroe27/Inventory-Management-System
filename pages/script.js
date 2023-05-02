
function enableInputFields(MeasurementID) {
    // get the name and contact input fields
    var input = document.getElementById("name" + MeasurementID);
    var editbtn = document.getElementById('editbtn' + MeasurementID);
    var save = document.getElementById('save' + MeasurementID)
    var cancel = document.getElementById('cancel' + MeasurementID)

    var inputs = document.querySelectorAll(".all" + MeasurementID);
    if (inputs) {
        inputs.forEach(input => {
            input.style.boxShadow = "rgba(99, 99, 99, 0.8) 0px 2px 8px 0px";
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

const navLinks = document.querySelectorAll('.navLinks a');

navLinks.forEach(link => {
    link.addEventListener('click', function () {
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
        this.classList.add('active');
    });
});
function showForm() {
    var form = document.getElementById("insert-form");
    form.style.display = "block";
}

function hideForm() {
    var form = document.getElementById("insert-form");
    form.style.display = "none";
}
setTimeout(function () {
    document.getElementById("message").style.display = "none";
}, 5000);