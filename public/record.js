/**
 * Validate an OHIP number
 * @param {string} ohip 
 * @returns {boolean} True if ohip number is valid
 */
function validateOHIP(ohip) {
    return /^\d{10}\w?\w?$/.exec(ohip);
}

/**
 * Set patient information from a JSON object
 * @param {Object | null} patient 
 */
function setPatientData(patient){
    let fn = document.getElementById("firstname");
    let ln = document.getElementById("lastname");
    let dob = document.getElementById("dob");
    if(patient == null){
        fn.disabled = false;
        ln.disabled = false;
        dob.disabled = false;
    } else {
        fn.disabled = true;
        fn.value = patient.firstName;
        ln.disabled = true;
        ln.value = patient.lastName;
        dob.disabled = true;
        dob.value = patient.dateOfBirth;
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
    fetch(`/api/getPatient?ohip=${ohip}`).then((resp)=>resp.json()).then((res)=> {
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
    fetch(`/api/getSiteData?site=${site}`).then((resp)=>resp.json()).then((res)=> {
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
    }
    // If there's a message, then the patient does not already exist
    if(document.getElementById("patient-message").innerText != "") {
        let data = new URLSearchParams();
        data.append("OHIP", document.getElementById("ohip").value);
        data.append("firstName", document.getElementById("firstname").value);
        data.append("lastName", document.getElementById("lastname").value);
        data.append("dateOfBirth", document.getElementById("dob").value);
        // Create new patient in background
        await fetch("/api/addPatient", {method:"post", body:data});  
    }
    // Create vaccination recotd
    let data = new URLSearchParams();
    data.append("patient", document.getElementById("ohip").value);
    data.append("site", document.getElementById("site").value);
    data.append("datetime", document.getElementById("date").value);
    data.append("lot", document.getElementById("lot").value);
    await fetch("/api/addVaccination", {method:"post", body:data});
}

window.onload = () => {
    setDates(document.getElementById("site").value);
}