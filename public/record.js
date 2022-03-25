/**
 * Validate an OHIP number
 * @param {string} ohip 
 * @returns {boolean} True if ohip number is valid
 */
function validateOHIP(ohip) {
    return /^\d{10}\w?\w?$/.exec(ohip);
}

function setPatientInputDisabled(disabled) {
    document.getElementById("firstname").disabled = disabled;
    document.getElementById("lastname").disabled = disabled;
    document.getElementById("dob").disabled = disabled;
}

/**
 * Set patient information from a JSON object
 * @param {Object | null} patient 
 */
function setPatientData(patient){
    if(patient == null){
        setPatientInputDisabled(false);
    } else {
        setPatientInputDisabled(true);
        document.getElementById("firstname").value = patient.firstName;
        document.getElementById("lastname").value = patient.lastName;
        document.getElementById("dob").value = patient.dateOfBirth;
    }
}

/**
 * Check if a patient exists, and if so populate their information
 * @param {string} ohip 
 */
function checkPatient(ohip) {
    if(!validateOHIP(ohip)){
        document.getElementById("patient-message").innerText = "Invalid OHIP number";
        setPatientData(null);
        return;
    }
    document.getElementById("patient-message").innerText = `Please Wait`;
    setPatientInputDisabled(true);
    fetch(`/public/api/getPatient.php?ohip=${ohip}`).then((resp)=>resp.json()).then((res)=> {
        if(res.success){
            document.getElementById("patient-message").innerText = "";
            setPatientData(res.patient);
        } else {
            setPatientData(null);
            document.getElementById("patient-message").innerText = "Patient not found. Please enter information";
        }
    })
}

/**
 * Set the date options based on a provided site
 * @param {string} site Name of site 
 */
function setDates(site){
    /** @type {HTMLSelectElement} */
    let dateInput = document.getElementById("date");
    let lotInput = document.getElementById("lot");
    // Disable while updating.
    dateInput.disabled = true;
    lotInput.disabled = true;
    fetch(`/public/api/getSiteData.php?site=${site}`).then((resp)=>resp.json()).then((res)=> {
        if(res.success){
            // Remove existing options
            for(let i=dateInput.options.length-1; i>=0; i--){
                dateInput.remove(i);
            }
            for(let d of res.data.dates){
                dateInput.add(new Option(d, d));
            }
            dateInput.disabled = false;
            // Remove existing options
            for(let i=lotInput.options.length-1; i>=0; i--){
                lotInput.remove(i);
            }
            for(let d of res.data.lots){
                lotInput.add(new Option(d, d));
            }
            lotInput.disabled = false;
        } else {
        }
    })
}

function validate(){
    // Check patient info
    if(!validateOHIP(document.getElementById("ohip").value)
        || document.getElementById("firstname").value.trim() == ""
        || document.getElementById("lastname").value.trim() == ""
        || document.getElementById("dob").value.trim == ""
        ) return false;
    // Check vaccine info
    if(document.getElementById("site").value == ""
        || document.getElementById("date").value == ""
        || !/^[\d\w]{6}$/.exec(document.getElementById("lot").value)
        ) return false;
    return true;
}

async function submit(){
    if(!validate()){
        alert("Invalid input");
        return;
    }
    let submitButton = document.getElementById("submit");
    // Prevent main click animation
    submitButton.classList.remove("clickable");
    submitButton.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i>`;
    submitButton.onclick = () => {};
    // If there's a message, then the patient does not already exist
    if(document.getElementById("patient-message").innerText != "") {
        let data = new URLSearchParams();
        data.append("OHIP", document.getElementById("ohip").value);
        data.append("firstName", document.getElementById("firstname").value);
        data.append("lastName", document.getElementById("lastname").value);
        data.append("dateOfBirth", document.getElementById("dob").value);
        // Create new patient in background
        await fetch("/public/api/addPatient.php", {method:"post", body:data});  
    }
    // Create vaccination recotd
    let data = new URLSearchParams();
    data.append("patient", document.getElementById("ohip").value);
    data.append("site", document.getElementById("site").value);
    data.append("datetime", document.getElementById("date").value);
    data.append("lot", document.getElementById("lot").value);
    await fetch("/public/api/addVaccination.php", {method:"post", body:data});

    // Redirect to patient info page
    window.location.replace(`/public/patients.php?p=${document.getElementById("ohip").value}`);
}

window.onload = () => {
    setDates(document.getElementById("site").value);
}