<?php

// add zeroes to zip code for displaying on page
$zipcodeAddedZeroes = "";
if (strlen($_POST['zipcode']) === 3) {
    $zipcodeAddedZeroes = "00" . $_POST['zipcode'];
} else if (strlen($_POST['zipcode']) === 4) {
    $zipcodeAddedZeroes = "0" . $_POST['zipcode'];
} else {
    $zipcodeAddedZeroes = $_POST['zipcode'];
}


// init empty arrays
$procedureCodeArray = $feeArray = array();
$descriptions = ['PERIODIX ORAL EXAMINATION', 'COMP ORAL EVALUATION - NEW/ESTABLISHED PATIENT', 'INTRAORAL - COMPLETE SERIES', 'INTRAORAL - PERIAPICAL 1 FILM', 'INTRAORAL - PERIAPICAL EACH ADDITIONAL FILM', 'BITEWINGS - TWO FILMS', 'BITEWINGS- FOUR FILMS', 'PAMORAMIC FILM', 'PROPHYLAXIS - ADULT', 'PROPHYLAXIS - CHILD', 'TOPICAL APPLICATION OF FLUORIDE', 'AMALGAM - TWO SURFACES PRIMARY OR PERMANENT', 'RESIN-BASED COMPOSITE - ONE SURFACE ANTERIOR', 'RESIN-BASED COMPOSITE - TWO SURFACES ANTERIOR', 'RESIN-BASED COMPOSITE - THREE SURFACES ANTERIOR', 'RESIN-BASED COMPOSITE - ONE SURFACE POSTERIOR', 'RESIN-BASED COMPOSITE - TWO SURFACES POSTERIOR', 'RESIN-BASED COMPOSITE - THREE SURFACES POSTERIOR', 'CROWN - PORCELAIN/CERAMIC', 'ROOT CANAL - MOLAR', 'PERIO SCALING AND ROOT PLANING - PER QUADRANT', 'PERIODONTAL MAINTENANCE', 'EXTRACTION ERUPTED TOOTH OR EXPOSED ROOT', 'SURG REMOVE ERUPTED TOOTH', 'REMOVAL OF IMPACTED TOOTH - COMPLETELY BONY'];

// Set errors to blank
$error = "";
$noRowsError = false;

// handle POST method
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["zipcode"] !== "") {

    // data validation on server side
    if (strlen($_POST["zipcode"]) < 3 || strlen($_POST["zipcode"] > 5)) {
        $error = "Invalid Zip Code";
    } else {
        $error = "";
    }
    if (!preg_match('/^[0-9]+$/', $_POST["zipcode"])) {
        $error = "no digits";
    } else {
        $error = "";
    }

    // function to open mysql db connection
    function OpenCon()
    {
        $dbhost = "removed";
        $dbuser = "removed";
        $dbpass = "removed";
        $db = "removed";
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

        return $conn;
    }

    // function to close mysql db connection
    function CloseCon($conn)
    {
        $conn->close();
    }

    // if there are no errors, run the query
    if ($error === "") {
        // create new connection
        $conn = OpenCon();

        if ($conn) {
            // create query
            $sql = "SELECT ProcedureCode, Fee from ProcedureFees WHERE ZipCode=? LIMIT 25";

            // prepare query
            $stmt = $conn->prepare($sql);

            // bind params 
            $stmt->bind_param("i", $zipcode);

            // set params 
            $zipcode = $_POST['zipcode'];

            //  execute params
            $stmt->execute();

            // fetch results
            $result = $stmt->get_result();

            // If no results do this
            if ($result->num_rows === 0) {
                $noRowsError = true;
            }

            // if there are results, push them to array
            while ($row = $result->fetch_assoc()) {
                array_push($procedureCodeArray, $row['ProcedureCode']);
                array_push($feeArray, $row['Fee']);
            }
        }
    }
}
?>
<html>

<head>
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .table-wrapper {
            margin: auto;
            width: 50%;
        }

        @media (max-width:1000px) {
            .table-wrapper {
                width: 100%;
            }
        }

        .title,
        .subtitle {
            color: #6495ED;
        }

        .info {
            margin: auto;
            text-align: center;
        }

        table {
            border-spacing: 0;
            margin: auto;
        }

        td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        th {
            color: white;
            background-color: #66b5ff;
            /* color for table headers */
            padding: 5px;
        }

        tr:nth-child(even) {
            background-color: #cce6ff;
            /* color for alternating rows */
        }

        .procedure-row-wrapper {
            text-align: center;
            border-right: 1px solid #b3daff;
            /* color for line in between rows */
        }

        .description-row-wrapper {
            padding-left: 10px;
            padding-right: 10px;
            border-right: 1px solid #b3daff;
            /* color for line in between rows */
        }

        .fee-row-wrapper {
            width: 20%;
        }

        .fee-row-wrapper div {
            width: 100%;
            display: flex;
            justify-content: space-apart;
        }

        .fee-row-data {
            display: flex;
            justify-content: flex-end;
            padding-right: 5px;
        }

        .fee-row-dollar {
            padding-left: 5px;
        }

        .back-button-wrapper {
            margin: auto;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .no-rows-found-wrapper {
            margin: auto;
            width: 50%;
            text-align: center;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php if (!$noRowsError) : ?>
        <div class="table-wrapper">
            <h1 class="title">Fee Schedule 2021</h2>
                <h3 class="subtitle">Network Fee Schedule (Zip Code <?= $zipcodeAddedZeroes; ?>)</h3>
                <div class="info">
                    <p>These are the network fees charged by most general dentists in the area.</p>
                    <p>Please note that fees for specialists and some general dentists may be higher.</p>
                    <p>Please contact your dentist to confirm network participation and fees <i><u>before</u></i> receiving treatment.</p>
                </div>
                <table>
                    <tr>
                        <th>PROCEDURE<br>CODE</th>
                        <th>DESCRIPTION</th>
                        <th>FEE</th>
                    </tr>
                    <?php foreach ($procedureCodeArray as $key => $procedureCode) : ?>
                        <tr>
                            <td class="procedure-row-wrapper"><b><?= $procedureCode; ?></b></td>
                            <td class="description-row-wrapper"><?= $descriptions[$key]; ?></td>
                            <td class="fee-row-wrapper">
                                <div>
                                    <div class="fee-row-dollar">$</div>
                                    <div class="fee-row-data"><?= $feeArray[$key]; ?>.00</div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <div class="info">
                    <p>Coverage is also provided for Dentures, Bridges, Implants, and Ortho. Contact [REMOVED] for information regarding these procedures.</p>
                    <p>[REMOVED] does not guarantee that a particular dentist will accept [REMOVED] fees as payment in
                        full. We rely upon the judgment of [REMOVED] as to the professional competency of dentists in their network. Our
                        role is to make the [REMOVED] network available to members of this program. Our liability is limited to the fee a
                        member pays for the card(s).</p>
                    <p>If you would like more information about any aspect of this program, please call<br>
                        <b>[REMOVED] at [REMOVED]</b></p>
                </div>

            <?php /* This is shown if the zip code is not found */ else : ?>
                <div class="no-rows-found-wrapper">
                    <h4 class="no-rows-text">Zip Code Not Found</h4>
                    <p> Try again?</p>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <p class="enter-zip-box">Enter zip code:</p>
                        <input id="zipcode-field" type="text" name="zipcode">
                        <p id="error" class="error"></p>
                        <input id="submit" type="submit" name="submit" value="Submit">
                    </form>
                </div>
                <script type="text/javascript" src="DataValidation.js"></script>
            <?php endif;
        CloseCon($conn);
            ?>
</body>

</html>