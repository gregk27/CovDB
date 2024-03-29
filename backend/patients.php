<?php
// Functions relating to patient information

include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Patient.php");
include_once(MODELS_DIR."Lot.php");

/**
 * Get information about a specified patient.
 * @param string $ohip Patient's OHIP number
 * @return Patient patient object representing specified patient
 */
function getPatient(string $ohip): Patient | null {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Patient WHERE OHIP=:ohip");
    $stmt->bindParam(":ohip", $ohip);
    $stmt->execute();
    $res = $stmt->fetch();
    if($res == null){
        return null;
    }
    return Patient::fromAssoc($res);
}

/**
 * Get a specicified patient and their vaccination information.
 * @param string $ohip Patient's OHIP number
 * @return array with structure:
 * {
 *   patient: Patient,
 *   vaccinations: [
 *     datetime: string,
 *     lot: Lot 
 *   ],
 *   numDoeses: number,
 * }
 */
function getPatientAndVaxInfo(string $ohip) {
    global $conn;
    // Get table with patient's vax records
    $stmt = $conn->prepare(
       "SELECT Patient.*, Vaccination.*, Lot.*, nd.* FROM Patient 
        LEFT OUTER JOIN Vaccination ON Vaccination.patient = Patient.OHIP 
        LEFT JOIN Lot ON Vaccination.lot = Lot.number 
        JOIN (SELECT COUNT(*) AS numDoses FROM Vaccination WHERE Vaccination.Patient=?) nd
        WHERE Patient.OHIP=?
        ORDER BY Vaccination.dateTime DESC");
    $stmt->execute([$ohip, $ohip]);
    $res = $stmt->fetchAll();

    // Read patient and count data from first entry (will be identical for all)
    $patient = Patient::fromAssoc($res[0]);
    $numDoses = $res[0]["numDoses"];

    $out = [];
    
    // Lot number null indicates no records exist for patient
    if($res[0]['lot'] != null){
        foreach($res as $row){
            $out[] = ["datetime"=>$row['datetime'], "lot"=>Lot::fromAssoc($row)];
        }
    }

    return ["patient"=>$patient, "vaccinations"=>$out, "numDoses"=>$numDoses];    
}

/**
 * Get a list of all patients in the database.
 * @param string $order Attribute to sort by, default OHIP
 * @return Patient[] with all patients in DB
 */
function getPatients(string $order = "OHIP") {
    global $conn;
    // Use array searching to sanitize column name
    $cols = array("OHIP", "firstName", "lastName", "dob");
    $order = $cols[array_search($order, $cols)];
    if($order == null) {
        return [];
    }
    $stmt = $conn->prepare("SELECT * FROM Patient ORDER BY ".$order);
    $stmt->execute();
    $out = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $out[] = Patient::fromAssoc($row);
    }
    return $out;
}

/**
 * Add a patient to the database
 * @param Patient $p patient data to be added
 */
function addPatient(Patient $p){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Patient (OHIP, firstName, lastName, dateOfBirth) VALUES (?, ?, ?, ?)");
    $stmt->execute([$p->OHIP, $p->firstName, $p->lastName, $p->dateOfBirth]);
}