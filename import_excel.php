<style>
    .card-body { background: #FFE9A2; }
</style>

<?php
if (!isset($_GET['folder_id'])) {
    $folder_id = 1;
} else {
    $folder_id = $_GET['folder_id'];
}

echo '<script>console.log("' . $folder_id . '");</script>';

if (!isset($_GET['folder_id'])) {
    echo '<script>console.log("no folder id");</script>';
}
?>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form method="" enctype="multipart/form-data" action="" id="import_excel">
                <input type="hidden" name="folder_id" value="<?php echo $folder_id ?>">
                <div class="row">
                    <div class="col-md-12" id="fileinputcont">
                        <div class="form-group">
                            <label for="inputGroupFile01">Select Excel File:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="importfile" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button type="submit" name="submit" class="btn btn-primary mr-2">Import</button>
                    <?php if (isset($_GET['folder_id'])) { ?>
                        <button class="btn btn-secondary" type="button" onclick="location.href = './index.php?page=folder&folder_id=<?php echo $folder_id; ?>'">Cancel</button>
                    <?php } else { ?>
                        <button class="btn btn-secondary" type="button" onclick="location.href = './index.php?page=record_list'">Cancel</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#import_excel').submit(function(e) {
        e.preventDefault();
        $('input').removeClass("border-danger");
        start_load();
        $('#msg').html('');

        $.ajax({
            url: 'ajax.php?action=import_excel',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Record Successfully Uploaded', "success");
                    <?php if (isset($_GET['folder_id'])) { ?>
                        setTimeout(function() {
                            location.href = './index.php?page=folder&folder_id=<?php echo $folder_id; ?>';
                        }, 2000);
                    <?php } else { ?>
                        setTimeout(function() {
                            location.href = './index.php?page=record_list';
                        }, 2000);
                    <?php } ?>
                } else if (resp == 2) {
                    alert_toast('Duplicate Entries Found', "error");
                    setTimeout(function() {
                        location.href = './index.php?page=record_list';
                    }, 2000);
                }
            }
        });
    });
</script>
