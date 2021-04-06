<div class="container">
  
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Additional Condition</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered">
            <tr>
              <th>Charge Name</th>
              <th>Charge Condition</th>
              <th>Charge Range</th>
              <th>Merchant Base</th>
              <th>Merchants</th>
              <th>Charge type</th>
              <th>Apply</th>
            </tr>
            <?php foreach ($model as $key => $value) {
             ?>
            <tr>
              <td><?= $value['charge_name']; ?></td>
              <td><?= $value['charge_condition']; ?></td>
              <td>
               <div class="dropdown">
                  <?= $value['charge_range']; ?>
                  <a class="dropdown-toggle" style="cursor:pointer;">view
                  <span class="caret"></span></a>
                  <div class="dropdown-data">
                  <table class="table table-striped table-bordered">
                    <tr>
                        <th>Fixed Range</th>
                        <th>From Range</th>
                        <th>To range</th>
                        <th>Amount type</th>
                        <th>Amount</th>
                    </tr>
                    <?php foreach ($value['conditional_range'] as $val) {
                      //print_r($val);die();?>
                      
                      <tr>
                        <td><?= $val['fixed_range']; ?></td>
                        <td><?= $val['from_range']; ?></td>
                        <td><?= $val['to_range']; ?></td>
                        <td><?= $val['amount_type']; ?></td>
                        <td><?= $val['amount']; ?></td>
                      </tr>
                      <?php
                    } ?>

              </table></div></div></td>
              <td><?= $value['merchant_base']; ?></td>
              <td><?= $value['merchants']; ?></td>
              <td><?= $value['charge_type']; ?></td>
              <td><?= $value['apply']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
        <div class="range-table" style="padding: 20px;"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
<script type="text/javascript">
  $(document).ready(function(){
   $(".dropdown-data").hide();
   $(".range-table").hide();
  });
  $(".dropdown-toggle").click(function(){
    var range = $(this).siblings('.dropdown-data').html();

    $(".range-table").html(range);
    $(".range-table").slideToggle();
});
</script>