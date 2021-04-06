<?php
	$this->title = 'Cedcommerce Admin Panel';
?>

<div class="site-index" >
	<div class="body-content">
    <?php 
     	//Yii::$app->ReportsDashboard->header();
	/*?>

   		<h2 style="text-align: center;font-family: verdana;">Graphical Representation of Jet-Integration App Details </h2>
     	<div class="row">
		   <head>
			  	<style>
			  	 *{
			  	 margin: 0;
			  	 padding: 0;
			  	 }
			  	svg{
			  		width: 97%;
			  		height: 500;
			  		
			  	}
			  	</style>
			    <script type="text/javascript">
			      google.charts.load("current", {packages:["corechart"]});
			      google.charts.setOnLoadCallback(drawChart);
			      function drawChart() {
			        var data = google.visualization.arrayToDataTable([
			          ['status', 'counts'],
			          ['App Install',       	<?php echo $extArray['install'];?>        ],
			          ['App Uninstall',     	<?php echo $extArray['uninstall'];?>      ],
			          ['App Not Purchased',     <?php echo $extArray['not_purchase'];?>   ],
			          ['App Purchased',    		<?php echo $extArray['purchased'];?>      ],
			          ['App Trial Expired',     <?php echo $extArray['trial_expired'];?>  ],
			          ['App Licence Expired',   <?php echo $extArray['license_expired'];?>],
			        ]);
			
			        var options = {
			          title: '',
			          pieHole: 0.4,
			        };			
			        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
			        chart.draw(data, options);
			      }
			    </script>
			    <script type="text/javascript">
			      google.charts.setOnLoadCallback(drawAreaChart);
			      function drawAreaChart() {
			        var areaChartData = google.visualization.arrayToDataTable([
			          ['status', 'counts'],
			          ['Current Month',  <?= $active['onemonth'];    ?>  ],
			          ['15 days',        <?= $active['fifteenday'];  ?>  ],
			          ['7 days',  	     <?= $active['sevenday'];    ?>  ],
			          ['Yesterday',  	 <?= $active['yesterday'];   ?>  ],
			          ['Today',  		 <?= $active['today'];       ?>  ],
			         ]);
			        var options = {
			          vAxis: {minValue: 0}
			        };
					var areaChart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			        areaChart.draw(areaChartData, options);
			      }								
			    </script>
			  </head>
			  <body style="background-color: #F0F0F0;">			  
			  	<div align="center" id="chart_div" style="width: 620px; height: 500px; float: left;"></div>			  	
			  	<div  align="center" id="donutchart" style="width: 550px; height: 360px; float: right;"></div>
			 </body>
		</div>
	*/?>	
	</div>
</div>