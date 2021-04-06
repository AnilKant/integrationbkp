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
                			<td>Total Uploaded Products</td>
                			<td><?= $data['uploaded'];?></td>
                		</tr>
                		<tr>
                			<td>Total Instock Products</td>
                			<td><?= $data['instock'];?></td>
                		</tr>
                		<tr>
                			<td>Total Outstock Products</td>
                			<td><?= $data['outstock'];?></td>
                		</tr>
                		<tr>
                			<td>Total Not Uploaded Products</td>
                			<td><?= $data['not_uploaded'];?></td>
                		</tr>
                		<tr>
                			<td>Total Fetched Order</td>
                			<td><?= $data['fetch_order'];?></td>
                		</tr>
                		<tr>
                			<td>Total Failed Order</td>
                			<td><?= $data['failed_order'];?></td>
                		</tr>
                		<tr>
                			<td>Total Order</td>
                			<td><?= $data['fetch_order'] +  $data['failed_order'];?></td>
                		</tr>
                		<tr>
                			<td>Total Revenue</td>
                			<td><?= $data['revenue'];?></td>
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
	
	h1{
		text-align: center;
	}
</style>