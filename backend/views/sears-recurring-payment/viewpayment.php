	<div class="container">
	  <!-- Modal -->
	  <div class="modal fade" id="myModal" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" style="text-align: center;font-family: "Comic Sans MS";">Recurring Payment Details</h4>
	        </div>
	        <div class="modal-body">
				<table class="table table-striped table-bordered" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td class="value_label" width="33%">
								<span>Recurring ID</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['id'] ?></span>
								
							</td>
						</tr>
						<tr>
							<td class="value_label" width="33%">
								<span>Plan Name</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['name'] ?></span>
							</td>
						</tr>
						<tr>
							<td class="value_label" width="33%">
								<span>Status</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['status'] ?></span>
							</td>
						</tr>
						<tr>
							<td class="value_label" width="33%">
								<span>Price</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['price'] ?></span>
							</td>
						</tr>
						<?php if(isset($data['billing_on']))
						{?>
							<tr>
								<td class="value_label" width="33%">
									<span>Billing On</span>
								</td>
								<td class="value form-group " width="100%">
									<span><?= $data['billing_on'] ?></span>
								</td>
							</tr>
						<?php 
						}?>
						<tr>
							<td class="value_label" width="33%">
								<span>Created At</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['created_at'] ?></span>
							</td>
						</tr>
						<tr>
							<td class="value_label" width="33%">
								<span>Updated At</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['updated_at'] ?></span>
							</td>
						</tr>
						<?php if(isset($data['trial_ends_on']))
						{?>
							<tr>
								<td class="value_label" width="33%">
									<span>Trial Ends On </span>
								</td>
								<td class="value form-group " width="100%">
									<span><?= $data['trial_ends_on'] ?></span>
								</td>
							</tr>
						<?php 
						}?>
						<?php if(isset($data['trial_days']))
						{?>
						<tr>
							<td class="value_label" width="33%">
								<span>Trial Days</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['trial_days'] ?></span>
							</td>
						</tr>
						<?php 
						}?>
						<tr>
							<td class="value_label" width="33%">
								<span>Decorated Return Url</span>
							</td>
							<td class="value form-group " width="100%">
								<span><?= $data['decorated_return_url'] ?></span>
							</td>
						</tr>		
					</tbody>
				</table>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
	      </div>	      
	    </div>
	  </div>	  
	</div>	