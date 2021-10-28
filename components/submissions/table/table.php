<?php 

    /**
     * *************************************
     * * Get table columns
     * *************************************
     */
    $query = "DESCRIBE `submissions` ";
    $stmt = $wpdb->prepare($query);
    $columns = $wpdb->get_results($stmt);
    $columns_array = array();

    foreach($columns as $column): 
        $columns_array[] = $column->Field;
    endforeach;
    // var_dump($columns_array);

    /**
     * *************************************
     * * Get submissions data
     * *************************************
     */
    $query = "SELECT * FROM `submissions` ";
    $stmt = $wpdb->prepare($query);
    $results = $wpdb->get_results($stmt);
?>

<div class="container-fluid px-5 my-5">
    <div class="row">
        <div class="col col-12">

            <?php if( !empty($columns_array) && !empty($results) ): ?>

                <!-- Filters -->
                <table class="mb-3" border="0" cellspacing="5" cellpadding="5">
                    <tbody>
                        <tr>
                            <td>Minimum Date:</td>
                            <td><input name="min" id="min" type="text" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Maximum Date:</td>
                            <td><input name="max" id="max" type="text" autocomplete="off"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Results -->
                <table id="submissions_table" class="table table-hover">
                    <thead>
                        <tr>
                            <?php foreach($columns_array as $column): ?>
                                <th scope="col">
                                    <?php 
                                        $column_name = ucwords(str_replace("_"," ", $column));
                                        echo $column_name; 
                                    ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $res): $res = (object) $res; ?>
                            <tr> 
                                <?php foreach($columns_array as $column): ?>
                                    <td><?php echo $res->$column; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>

                <p>No results found.</p>

            <?php endif; ?>

        </div>
    </div>
</div>
