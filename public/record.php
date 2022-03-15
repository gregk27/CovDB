<!DOCTYPE html>
<html>
<?php 
    define("NAME", "Record Vaccination");
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."sites.php");
    include_once(COMMON_ELEMENTS);
?>
<head>
    <?php insertMeta(); ?>
    <script src='record.js'></script>
</head>

<body>
    <?php insertHeader(); ?>
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
                <label for="lot">Lot Number:</label><select id="lot"> </select>
            </form>
        </div>
    </section>
    <br/>
    <button id="submit" class="clickable" style="margin:auto; font-size:1.5em; display:block" onclick="submit()">Submit</button>
</body>

</html>