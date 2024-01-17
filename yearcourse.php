<style>
    .info-box {
        font-size: 12px;
        height: 2px;
    }
    .search-bar-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        float: right;
    }

    .search-bar {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }
    .card-header {
        background: #FFE9A2;
            font-weight: bold;
           
    }
    
</style>



<?php
// Check if the year parameter is set in the URL
if (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];

    // Query the database to get the distinct course names for the selected year
    $queryCourse = "SELECT DISTINCT course_name FROM record WHERE YEAR_ENTRY = '$selectedYear'";
    $resultCourse = $conn->query($queryCourse);
?> 
    <h3><?php echo $_GET['year'] ?> Entry Records<div class="search-bar-container">
    <input type="text" class="fas fa-search search-bar" placeholder="Search..." oninput="handleSearch(event)">
</div><br></h3>
    <hr class="border-success">

    <?php while ($courseRow = $resultCourse->fetch_assoc()) {
        $courseName = $courseRow['course_name'];

        // Query the database to get the count of records for the selected year and course name
        $queryCount = "SELECT COUNT(*) AS recordCount FROM record WHERE YEAR_ENTRY = '$selectedYear' AND course_name = '$courseName'";
        $resultCount = $conn->query($queryCount);
        $recordCount = $resultCount->fetch_assoc()['recordCount'];
    ?>
        <div class="card">
            <a href="./index.php?page=year&course=<?php echo $courseName; ?>&year=<?php echo $selectedYear; ?>">
            <div class="card-header">
                
                    <i class="fas fa-folder"></i>
                    [<?php echo  ($recordCount);?>]
                    <b style="color:black"><?php echo $courseName; ?></b>
               
            </div>
        </div>
        </a>
    <?php } ?>

<?php
}
?>

<script>
    function handleSearch(event) {
        const searchQuery = event.target.value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const courseName = card.querySelector('b').textContent.toLowerCase();
            if (courseName.includes(searchQuery)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
