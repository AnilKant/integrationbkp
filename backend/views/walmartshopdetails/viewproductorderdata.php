<?php
/**
 * Created by PhpStorm.
 * User: cedcoss
 * Date: 5/7/19
 * Time: 3:52 PM
 */
?>
<div class="container">
    <div class="modal fade" tabindex="-1" id="myModal" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content" id='edit-content'>
                <div class="modal-header">
                    <h1>View Product & Order Data</h1>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td>Total Published Products</td>
                            <td><?= $data['published']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Item Products</td>
                            <td><?= $data['item_processing']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Unpublished Products</td>
                            <td><?= $data['unpublished']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Not Uploaded Products</td>
                            <td><?= $data['not_uploaded']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Stage Products</td>
                            <td><?= $data['stage']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Products</td>
                            <td><?= $data['total_products']; ?></td>
                        </tr>

                        <tr>
                            <td>Total Completed Order</td>
                            <td><?= $data['completed']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Acknowledged Order</td>
                            <td><?= $data['acknowledged']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Cancelled Order</td>
                            <td><?= $data['cancelled']; ?></td>
                        </tr>
                        <tr>
                            <td>Total Failed Order</td>
                            <td><?= $data['failed_order']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer Attrubute_html">
                    <button type="button" class="btn btn-default" id="edit-modal-close" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">

    h1 {
        text-align: center;
    }
</style>