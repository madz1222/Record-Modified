<style>
    .card-header {
        background: #FFE9A2;
            font-weight: bold;
           width: 100%;
    }
    .card {
       
           width: 19%;
           margin: 5px;
    }
    .info-box{
        border: none;
    }
    .infotrash{
        background: darkred;
    }
</style>
<?php include('db_connect.php'); ?>
<!-- Info boxes -->
<?php if($_SESSION['login_type'] == 1): ?>
    <h2>Home</h2>
     
    <hr class="border-success">
    <br>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3" >
        <a href="./index.php?page=user_list"> 
            <div class="info-box" class="bg">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                
                    <div class="info-box-content">
                        <span class="info-box-text"><b style="color:black">Users</b></span>
                        <span class="info-box-number">
                            <?php echo $conn->query("SELECT * FROM users")->num_rows; ?>
                        </span>
                    </div>
               
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        </a>

        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
        <a href="./index.php?page=record_list"> 
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-folder"></i></span>
              
                    <div class="info-box-content">
                        <span class="info-box-text"><b style="color:black">Records</b></span>
                        <span class="info-box-number">
                            <?php echo $conn->query("SELECT * FROM record")->num_rows; ?>
                        </span>
                    </div>
        
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        </a>

    
    <div class="col-12 col-sm-6 col-md-3">
    <a href="./index.php?page=manage_folders"> 
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"> <i class="nav-icon fa fa-folder-open"></i></span>
             
                    <div class="info-box-content">
                        <span class="info-box-text"><b style="color:black">Manage Folders</b></span>
                        <span class="info-box-number">
                            <?php echo $conn->query("SELECT * FROM folders")->num_rows; ?>
                        </span>
                    </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        </a>
<style>
.infoTRASHBIN{background-color: rgb(150,150,150); border: 2px solid black;}
</style>

    <div class="col-12 col-sm-6 col-md-3">
    <a href="./index.php?page=trash_records"> 
            <div class="infotrash info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="nav-icon fa fa-trash"></i></span>
             
                 
                        <span class="info-box-text"><b style="color:gold">&nbsp; Trash Bin</b></span>

                    </div>
             
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        
    </a>

   <br>
    <hr class="border-success">
    <!-- YEAR ENTRY -->
    <h2>Yearly Entry Records</h2>
    <br>
    <div class="row">
        <?php
        // Query the database to get the distinct years in the record table in ascending order
        $query = "SELECT DISTINCT YEAR_ENTRY FROM record ORDER BY YEAR_ENTRY DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $yeargraduate = $row['YEAR_ENTRY'];
            // Query the database to get the count of records for each year
            $queryCount = "SELECT COUNT(*) AS recordCount FROM record WHERE YEAR_ENTRY = '$yeargraduate'";
            $resultCount = $conn->query($queryCount);
            $recordCount = $resultCount->fetch_assoc()['recordCount'];
        ?>
           <div class="card ">
               <a href="./index.php?page=yearcourse&year=<?php echo $yeargraduate; ?>">
            <div class="card-header col-12">
         
               
                   <i class="fas fa-folder"></i></span>
                   [<?php echo $recordCount; ?>]
                   
                    <b style="color:black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $yeargraduate; ?></b>
                           
                         <!-- /.info-box-content -->
                  
                </div>
                <!-- /.info-box -->
            </div>
            </a>
            
        <?php
        }
        ?>
    </div>
    <?php elseif($_SESSION['login_type'] == 2): ?>
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-folder"></i></span>
                <a href="./index.php?page=record_list">
                       <div class="info-box-content">
                        <span class="info-box-text"><b style="color:black">Records</b></span>
                        <span class="info-box-number">
                        <b style='color:black' ><?php echo $conn->query("SELECT * FROM record  where clerk_id = {$_SESSION['login_id']}")->num_rows; ?></b>
                        </span>
                  
    

                </a>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
    
    
   
    <?php else: ?>
     
    
   
    <!-- YEAR ENTRY -->
    <h2>Yearly Entry Records</h2>
    <br>
    <div class="row">
        <?php
        // Query the database to get the distinct years in the record table in ascending order
        $query = "SELECT DISTINCT YEAR_ENTRY FROM record ORDER BY YEAR_ENTRY DESC";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $yeargraduate = $row['YEAR_ENTRY'];
            // Query the database to get the count of records for each year
            $queryCount = "SELECT COUNT(*) AS recordCount FROM record WHERE YEAR_ENTRY = '$yeargraduate'";
            $resultCount = $conn->query($queryCount);
            $recordCount = $resultCount->fetch_assoc()['recordCount'];
        ?>
            <div class="card ">
               <a href="./index.php?page=yearcourse&year=<?php echo $yeargraduate; ?>">
            <div class="card-header col-12">
         
               
                   <i class="fas fa-folder"></i></span>
                   [<?php echo $recordCount; ?>]
                   
                    <b style="color:black">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $yeargraduate; ?></b>
                           
                         <!-- /.info-box-content -->
                  
                </div>
                <!-- /.info-box -->
            </div>
            </a>
        <?php
        }
        ?>
    </div>
    
<?php endif; ?>
