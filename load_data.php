<?php
    // Get the page number and number of records per page from DataTables
    $start = $_POST['start'];
    $length = $_POST['length'];

    $i = 1;
    $where = " WHERE record_status = 'notdeleted' ";
    if ($_SESSION['login_type'] == 1) {
        $user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
        while ($row = $user->fetch_assoc()) {
            $uname[$row['id']] = ucwords($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename']);
        }
    } else {
        $where .= " AND clerk_id = '{$_SESSION['login_id']}'";
    }

    $qry = $conn->query("SELECT * FROM record $where ORDER BY UNIX_TIMESTAMP(date_created) DESC LIMIT $start, $length");

    // Prepare the data array to hold the records
    $data = array();
    while ($row = $qry->fetch_assoc()) {
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);

        // Create an array for each row
        $data[] = array(
            ucwords($row['id']),
            ucwords($row['last_name']),
            ucwords($row['first_name']),
            ucwords($row['middle_name']),
            ucwords($row['last_name']) . ', ' . ucwords($row['first_name']) . ', ' . ucwords($row['middle_name']),
            ucwords($row['course_name']),
            ucwords($row['year_entry']),
            ucwords($row['year_graduate']),
            ucwords($row['grad_hd'])
        );
    }

    // Prepare the response JSON
    $response = array(
        "draw" => intval($_POST['draw']),
        "recordsTotal" => $total_records, // Total number of records (without filtering)
        "recordsFiltered" => $filtered_records, // Total number of records (with filtering)
        "data" => $data // Array of records to display
    );

    // Return the response as JSON
    echo json_encode($response);
?>
