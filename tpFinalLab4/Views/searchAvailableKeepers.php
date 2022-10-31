<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Available Keepers</title>
    <link href="<?php echo CSS_PATH?>styles.css" rel="stylesheet" media="all">
</head>

<body>
    <?php include("nav.php"); ?>
    
    <section class="calendar-principal">
        <div class="calendar-choise">
            <form action="<?php echo FRONT_ROOT ?>Calendar/ShowAvailableKeepers" method="POST">
                <h1>Define Period</h1>
                <div class="form-content-calendar">
                    <label for="">Start Date</label>
                    <input type="date" name="startDate">
                    
                </div>
                <div class="form-content-calendar">
                    <label for="">End Date</label>
                    <input type="date" name="endDate">
                </div>
                <div class="">
                                <label for="">Select pet</label>
                                <select name="petid">
                                    <?php foreach($petList as $pet)
                                    {
                                        ?>
                                        <option value="<?php echo $pet->getIdPet()?>"><?php echo $pet->getName()?></option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                </div>
                
                <button type="submit" class="btn-calendar">Search</button>

            </form>
            <a class="cancel-register" href="<?php echo FRONT_ROOT ?>Owner/OwnerLogin">Cancel search</a>
        </div>

    </section>


</body>

</html>