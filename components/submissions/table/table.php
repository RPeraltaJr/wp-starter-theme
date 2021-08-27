<?php 
    $query = "SELECT * FROM `submissions` ";
    $stmt = $wpdb->prepare($query);
    $results = $wpdb->get_results($stmt);
?>

<div class="container my-5">
    <div class="row">
        <div class="col col-12">

            <?php if( !empty($results) ): ?>
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
                <table id="submissions_table" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Timestamp</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Form ID</th>
                            <th scope="col">URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $x = 1; 
                            foreach($results as $res): 
                                $res = (object) $res;
                        ?>
                        <tr> 
                            <th scope="row"><?php echo $res->id; ?></th>
                            <td>
                                <?php 
                                    $date = $res->timestamp;
                                    $newDate = date("m/d/Y", strtotime($date));
                                    echo $newDate;
                                ?>
                            </td>
                            <td><?php echo $res->full_name; ?></td>
                            <td><?php echo $res->phone; ?></td>
                            <td><?php echo $res->email; ?></td>
                            <td><?php echo $res->form_id; ?></td>
                            <td><?php echo $res->form_url; ?></td>
                        </tr>
                        <?php $x++; endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>

        </div>
    </div>
</div>
