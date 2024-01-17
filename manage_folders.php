<style>
    .folder-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .search-bar-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .search-bar {
        width: 20%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .btndelete {
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .card-header {
        background: #00b300;
    }

    .card-body {
        background: #FFE9A2;
    }
</style>

<!-- Add the folder creation form -->
<center>
    <div class="col-8 col-sm-6 col-md-4">
        <div class="info-box">
            <span class=""><i class="fa fa-folder-open"></i></span>
            <a href="#">
                <div class="info-box-content">
                    <div>
                        <span class="info-box-text"><b style="color:black">Create Folder</b></span>
                        <form method="POST" action="">
                            <input type="text" name="folder_name" placeholder="Folder Name" required>
                            <button type="submit" name="create_folder" class="btn btn-primary btn-sm">Create</button>
                        </form>
                    </div>
                </div>
            </a>
        </div>
    </div>
</center>

<?php
if ($_SESSION['login_type'] == 1) {
    // Code for admin or user with login_type 1
    if (isset($_POST['create_folder'])) {
        $folderName = $_POST['folder_name'];
        // Code to insert the folder information into the database
        // Assuming you have a table named 'folders' with columns 'folder_name' and 'parent_folder_id'
        $parentFolderId = 0; // Assuming the folder is created at the root level

        // Prepare the insert query
        $insertQuery = "INSERT INTO folders (folder_name, parent_folder_id) VALUES ('$folderName', $parentFolderId)";

        // Execute the insert query
        $result = $conn->query($insertQuery);
    }

    if (isset($_POST['rename_folder'])) {
        $folderId = $_POST['folder_id'];
        $newFolderName = $_POST['new_folder_name'];
        // Code to update the folder name in the database
        // Assuming you have a table named 'folders' with columns 'id' and 'folder_name'

        // Prepare the update query
        $updateQuery = "UPDATE folders SET folder_name='$newFolderName' WHERE id=$folderId";

        // Execute the update query
        $result = $conn->query($updateQuery);
    }
}
?>

<hr class="border-success">
<div class="col-sm-12">
    <div class="card card-outline">
        <div class="card-header">
            <div class="card-tools mr-2">
                <a button id="show-delete-buttons" class="btn btn-primary">Action</button></a>
            </div>
            <!-- Add the search bar -->
            <div class="search-bar-container">
                <input type="text" id="search-bar" class="fas fa-search search-bar" placeholder="Search folder"
                    onkeyup="searchFolders()">
            </div>
        </div>
        <br>
        <!-- Display existing folders -->
        <div class="row" id="folder-list">
            <?php
            // Query the database to retrieve the folders
            $foldersQuery = "SELECT * FROM folders";
            $foldersResult = $conn->query($foldersQuery);

            // Check if any folders exist
            if ($foldersResult->num_rows > 0) {
                while ($folderRow = $foldersResult->fetch_assoc()) {
                    $folderId = $folderRow['id'];
                    $folderName = $folderRow['folder_name'];
            ?>
            <?php if ($_SESSION['login_type'] == 1): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class=""><i class="fa fa-folder-open"></i></span>
                    <div class="columncontent">
                        <a href="./index.php?page=folder&folder_id=<?php echo $folderId; ?>">
                            <div class="info-box-content">
                                <span class="info-box-text"><b style="color:black"><?php echo $folderName; ?></b></span>
                            </div>
                        </a>
                        <form method="POST" action="">
                            <div class="info-box-action">
                                <input type="hidden" name="folder_id" value="<?php echo $folderId; ?>">
                                <input type="text" name="new_folder_name" placeholder="New Folder Name" required>
                                <button type="submit" name="rename_folder"
                                    class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-sm delete_folder"
                                    data-folder-id="<?php echo $folderId; ?>"><i class="fas fa-trash"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php
                }
                $foldersResult->free_result();
            } else {
                echo "";
            }
            ?>
        </div>
    </div>
</div>

<script>
    // Event delegation for record deletion
    $(document).on('click', '.delete_folder', function () {
        var folder_id = $(this).data('folder-id');
        console.log(folder_id); // Log the parameter values

        _conf("Are you sure to delete this folder?  ", "delete_folder", [folder_id]);
    });

    function delete_folder($folder_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_folder_second',
            method: 'POST',
            data: {
                folder_id: $folder_id
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Folder Successfully Deleted", 'success')
                    setTimeout(function () {
                        window.location.href = 'index.php?page=manage_folders';
                    }, 500);
                }
            }
        })
    }

    function searchFolders() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('search-bar');
        filter = input.value.toUpperCase();
        ul = document.getElementById("folder-list");
        li = ul.getElementsByClassName('col-12 col-sm-6 col-md-4');

        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("b")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    $(document).ready(function () {
    $('.delete_folder, .btn-primary.btn-sm[name="rename_folder"], input[name="new_folder_name"]').hide();

    $('#show-delete-buttons').click(function () {
        $('.delete_folder, .btn-primary.btn-sm[name="rename_folder"], input[name="new_folder_name"]').toggle();
    });
});


</script>
