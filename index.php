<?php

require_once('db.php');

$db = new db();

if (count($_POST) > 0) {
    if (array_key_exists("action", $_POST)) {
        switch ($_POST['action']) {
            case "delete":
                $params = $_POST;
                unset($params['action']);
                $success = $db->delete($params);
                if ($success) {
                    echo "record deleted";
                }
                else {
                    echo "error deleting record";
                }
                break;
            case "insert":
                $params = $_POST;
                unset($params['action']);
//                echo "<pre>";
//                echo __LINE__ . ":" . print_r($params, true) . "\n";

                $success = $db->insert($params);
//                echo __LINE__ . ":" . print_r($success, true) . "\n";

//                echo "</pre>";
                if ($success) {
                    echo "record inserted";
                }
                else {
                    echo "error inserting record";
                }
                break;
        }
    }
}

$results = $db->fetch();

$states = [
    'OH',
    'FL',
    'ME'
];
?>
<html>
    <head>
        <script>
            function validateForm() {
                // values entered checks
                var newName = document.forms["frmNewEntry"]["txtNewName"].value;
                var newEmail = document.forms["frmNewEntry"]["txtNewEmail"].value;
                var newState = document.forms["frmNewEntry"]["drpNewState"];
                var newStateSelected = newState.options[newState.selectedIndex].text;

                if (newName == "") {
                    alert("Name must be filled out");
                    return false;
                }

                if (newEmail == "") {
                    alert("Email must be filled out");
                    return false;
                }

                // value format checks
                var regExAlpha = /^[a-zA-Z\s]*$/;
                var regExEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

                if (!regExAlpha.test(newName)) {
                    alert("Name can only contain letters and spaces.");
                    return false;
                }

                if (!regExEmail.test(newEmail)) {
                    alert("Email must be properly formatted.");
                    return false;
                }

                // dropdown value check
                var validStates = <?php echo json_encode($states); ?>;

                var exists = false;
                for(var i = 0; i < validStates.length; i++) {
                    if( validStates[i] === newStateSelected ) {
                        exists = true;
                        break;
                    }
                }
                if (!exists) {
                    alert("You must choose form the available options");
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <table>
            <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Email</td>
                <td>State</td>
                <td>Interested</td>
                <td>&nbsp;</td>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($results as $result) {
                ?>
                <tr>
                    <td><?php echo $result['ID']; ?></td>
                    <td><?php echo $result['Name']; ?></td>
                    <td><?php echo $result['Email']; ?></td>
                    <td><?php echo $result['State']; ?></td>
                    <td><?php echo ($result['Interested'] == 1 ? "Yes" : "No"); ?></td>
                    <td>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="ID" value="<?php echo $result['ID']; ?>">
                            <button>delete</button>
                        </form>
                    </td>
                </tr>

                <?php
            }
            ?>
            <form method="POST" action="index.php" id="frmNewEntry" onsubmit="return validateForm()">
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="text" name="Name" id="txtNewName" /></td>
                    <td><input type="text" name="Email" id="txtNewEmail" /></td>
                    <td>
                        <select name="State" id="drpNewState">
                            <?php
                            foreach ($states as $state) {
                                ?>
                                <option><?php echo $state; ?></option>
                                <?php
                            }
                            ?>
                            ?>
                        </select>
                    </td>
                    <td><input type="checkbox" name="Interested" id="chkNewInterested" value="1" /> </td>
                    <td>
                        <input type="hidden" name="action" value="insert">
                        <button>insert</button>
                    </td>
                </tr>
            </form>
            </tbody>
        </table>
    </body>
</html>
