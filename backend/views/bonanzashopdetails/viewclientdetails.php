<?php
use yii\helpers\Html;
?>
<div class="container">
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" id="myModal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content" id='edit-content' style="width: 103%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="text-align: center;font-family:'Comic Sans MS';">Bonanza client details</h4>
                </div>
                <div class="modal-body">
                    <div class="jet-product-form">
                        <div class="form-group">
                            <div class="field-jetproduct">
                                <?php
                                if ($error) {
                                    echo "<h4 class='center'> Client detail not exist</h4>";
                                } else {
                                    ?>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>mobile</th>
                                                <th>time_zone</th>
                                                <th>time_slot</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= $data['name']; ?></td>
                                                <td><?= $data['mobile']; ?></td>
                                                <td><?= $data['time_zone']; ?></td>
                                                <td><?= $data['time_slot']; ?></td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th>selling_on_bonanza</th>
                                                <th>selling_on_bonanza_source</th>
                                                <th>reference</th>
                                                <th>other_reference</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= $data['selling_on_bonanza']; ?></td>
                                                <td><?= $data['selling_on_bonanza_source']; ?></td>
                                                <td><?= $data['reference']; ?></td>
                                                <td><?= $data['other_reference']; ?></td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th colspan="4">Other marketplace</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4"><?= $data['other_mps']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>