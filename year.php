<!DOCTYPE html>
<html>
<head>
    <title>Record Details</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <style>
        .card {
            background: #FFE9A2;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: auto;
        }
        
        .card-header {
            background: lightgreen;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .table-container {
           
            overflow-x: auto;
            max-width: 100%;
        }
        
        table {
      
            border-collapse: collapse;
            white-space: nowrap;
            width: 100%;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
        
        th,
        td {
          
            padding: 8px;
            border: 1px solid #ccc;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<?php 
$val = "year";
$year = isset($_GET['year']) ? $_GET['year'] : "none";
$course = isset($_GET['course']) ? $_GET['course'] : "none";
?>
            <div class="card">
                <div class="card-header">
                <div class="card-tools ml-4">
                    <a class="btn btn-sm btn-secondary btn-flat" href="./index.php?page=yearcourse&year=<?php echo $year; ?>"><i class="bi bi-chevron-left"></i> Back</a>
                </div>

                <?php if ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 3): ?>
                    <div class="card-tools ">
                        <a class="btn btn-block btn-sm btn-info btn-flat export_excel" href="ajax.php?action=export_excel_year&year=<?php echo $year ?>&course=<?php echo $course ?>"><i class="fa fa-download"></i> Export Excel</a>
                    </div>
                <?php endif; ?>

                Year Entry:&nbsp; <?php echo $year; ?> <br> Course: &nbsp;<?php echo $course; ?>
                   
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table id="recordTable" class="display">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Date Created</th>
                                    <th>Control No</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if the 'year' and 'course' parameters are set in the URL
                                if (isset($_GET['year']) && isset($_GET['course'])) {
                                    $year = $_GET['year'];
                                    $course = urldecode($_GET['course']);

                                    // Query the database to retrieve records for the specified year and course
                                    $query = "SELECT * FROM record WHERE year_entry = '$year' AND course_name = '$course' AND record_status = 'notdeleted'";
                                    $result = $conn->query($query);

                                    // Check if records are found for the specified year and course
                                    if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group">
                                            <?php if ($_SESSION['login_type'] == 1): ?>
                                                <button type="button" class="btn btn-danger btn-flat trash_record" data-id="<?php echo $row['id']?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                                <a href="./index.php?page=update_record&id=<?php echo $row['id'] ?>&val=<?php echo $val ?>&course=<?php echo $course ?>&year=<?php echo $year ?>" class="btn btn-primary btn-flat">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($_SESSION['login_type'] == 1): ?>
                                                <a href="./index.php?page=view_files&student_no=<?php echo $row['id'] ?>&val=<?php echo $val ?>" class="btn btn-info btn-flat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($_SESSION['login_type'] == 3): ?>
                                                <a href="./index.php?page=view_all_files&student_no=<?php echo $row['id'] ?>&val=<?php echo $val ?>" class="btn btn-info btn-flat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                              <!--  <?php if ($_SESSION['login_type'] == 1): ?>
                                                    <button type="button" class="btn btn-danger btn-flat trash_record" data-id="<?php echo $row['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>-->
                                            </div>
                                        </td>
                                        <td><?php echo $row['date_created']; ?></td>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['first_name']; ?></td>
                                        <td><?php echo $row['middle_name']; ?></td>
                                        <td><?php echo $row['last_name']; ?></td>
                                        <td><?php echo $row['grad_hd']; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    
            <script>
    $(document).ready(function() {
        $('#recordTable').DataTable({
            
        });

        $('#recordTable').on('click', '.trash_record', function() {
            var record_id = $(this).data('id');
            console.log(record_id); // Log the parameter values

            _conf("Are you sure to delete this record? <br><br><small> Linked files will also be deleted</small>",
                "trash_record",
                [record_id]);
        });
    });

    function trash_record($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=trash_record',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Record Successfully Deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },500)

                }
            }
        })
    }

    function printTable() {
        var yearEntry = '<?php echo $year; ?>';
        var courseName = '<?php echo $course; ?>';
        var printContents = document.getElementById('recordTable').outerHTML;
        var printWindow = window.open('', '', 'width=800, height=600');

        printWindow.document.write('<html><head><title>Print</title><style>table { border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 8px; }</style></head><body>');
        printWindow.document.write('<table><thead><tr><th colspan="9" style="text-align: center;">Record Details - Year Entry: ' + yearEntry + ' - Course: ' + courseName + '</th></tr></thead><tbody>');
        printWindow.document.write(printContents);
        printWindow.document.write('</tbody></table>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    // Handle individual record deletion
    $('#recordTable').on('click', '.trash_record', function() {
        var record_id = $(this).data('id');
        console.log(record_id); // Log the parameter value

        _conf("Are you sure to delete this record? <br><br><small> Linked files will also be deleted</small>", "trash_record", [record_id]);
    });

    function trash_multiple_records(record_ids) {
        $.ajax({
            url: "ajax.php?action=trash_multiple_records",
            method: "POST",
            data: {
                delete: true,
                record_ids: record_ids,
            },
            success: function(response) {
                if (response == 1) {
                    alert_toast("Records Successfully Deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else if (response == 2) {
                    alert_toast("Error Deleting Records", 'danger');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
        });
    }

    function trash_record($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=trash_record',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Record Successfully Deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 500)
                }
            }
        })
    }
</script>
</body>
</html>
