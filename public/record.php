<!DOCTYPE html>
<html>
<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
include_once(BACKEND_DIR."sites.php");
?>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>CoVDB - Record</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <!-- Inlcude FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/60394e1dab.js" crossorigin="anonymous"></script>
    <script src='record.js'></script>
</head>

<body>
    <header>
        <h1>CoVDB - Record Vaccine</h1>
    </header>
    <section class="panelgrid">
        <div class="panel">
            <i class="fa-solid fa-user"></i>
            <h2>Patient Information</h2>
            <form id="patient-info">
                <label for="ohip">OHIP Number:</label><input id="ohip" oninput="checkPatient(this.value)"/>
                <p class="message" id="patient-message">Error message</p>
                <label for="firstname">First name:</label><input id="firstname" />
                <label for="lastname">First name:</label><input id="lastname" />
                <label for="dob">Date of Birth:</label><input id="dob" type="date" />
            </form>
        </div>

        <div class="panel">
            <i class="fa-solid fa-synringe"></i>
            <h2>Vaccine Information</h2>
            <form id="patient-info">
                <label for="site">Site:</label><select id="site" onchange="setDates(this.value)">
                    <?php 
                        foreach(getSites() as $s){
                            echo "<option value='".$s->name."'>".$s->name." - ".$s->street." ".$s->city.", ".$s->province."</option>";
                        }
                    ?>
                </select>
                <label for="date">Date Administered:</label><select id="date"></select>
                <label for="lot">Lot Number:</label><input id="lot" />
            </form>
        </div>
    </section>
    <br/>
    <button class="clickable" style="margin:auto; font-size:1.5em; display:block" onclick="submit()">Submit</button>
</body>

</html>