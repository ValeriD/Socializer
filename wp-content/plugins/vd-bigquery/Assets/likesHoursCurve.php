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
                ['Hour', 'Likes'],
				<?php
				foreach($data as $row)
				{
					echo "['".$row['f0_']->format('H')."', ".$row['f1_']."],";
				}
				?>
            ]);
            var options = {
                title: 'Likes per hour',
                curveType:'function',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('lineChart'));
            chart.draw(data, options);
        }
	</script>
</head>
<body>
<br /><br />
<div style="width:900px;">
	<div id="lineChart" style="width: 500px; height: 500px;"></div>
</div>
</body>
</html>
