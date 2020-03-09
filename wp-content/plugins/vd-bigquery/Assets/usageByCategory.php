<?php

?>
<html>
<head>
	<title>Webslesson Tutorial | Make Simple Pie Chart by Google Chart API with PHP Mysql</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart()
        {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'PostCount'],
				<?php
				foreach($data as $row)
				{
					echo "['".$row["post_category"]."', ".$row["f0_"]."],";
				}
				?>
            ]);
            var options = {
                title: 'Percentage of Social network used',
                is3D:true,
                pieHole: 0.4
            };
            var chart = new google.visualization.PieChart(document.getElementById('chart'));
            chart.draw(data, options);
        }
	</script>
</head>
<body>
<br /><br />
<div style="width:900px;">
	<div id="chart" style="width: 500px; height: 500px;"></div>
</div>
</body>
</html>
