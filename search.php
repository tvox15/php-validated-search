<html>

<head>
    <style type="text/css">
        .search-box {
            margin: auto;
            width: 50%;
        }

        .search-box-content h3,
        p,
        form {
            text-align: center;
        }

        .enter-zip-box {
            margin-bottom: 3px;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="search-box">
        <div class="search-box-content">
            <h3><i> Network Fees</i></h3>
            <p> General Dentists' Fees</p>
            <p><i>(Specialists' fees may be higher)</i></p>
            <p class="enter-zip-box">Enter zip code:</p>
            <form method="post" action="results.php" target="_blank">
                <input id="zipcode-field" type="text" name="zipcode">
                <p id="error" class="error"></p>
                <input id="submit" type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>
    
    <script type="text/javascript" src="DataValidation.js"></script>
</body>

</html>