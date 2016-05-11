<html>
	<head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  		<script type="text/javascript">
    			google.charts.load("current", {packages:['corechart']});
    			google.charts.setOnLoadCallback(drawCharts);	
			
			function drawCharts() {
				@for($i = 0; $i < count($categories); $i++) 
					var {{ $categories[$i] }} = google.visualization.arrayToDataTable([
						['Tier', '{{ $categories[$i] }}', {role: 'style'}],
						['You',{{ $summonerAvg[$categories[$i]] }} , '#0247fe'],
						['Bronze', {{ $globalAvg['bronze'][$categories[$i]] }}, '#b87333'],
						['Silver', {{ $globalAvg['silver'][$categories[$i]] }}, '#c0c0c0'],
						['Gold', {{ $globalAvg['gold'][$categories[$i]] }}, '#ffd700'],
						['Platinum', {{ $globalAvg['plat'][$categories[$i]] }}, '#e5e4e2"'],
						['Diamond', {{ $globalAvg['diamond'][$categories[$i]] }}, '#b9f2ff'],
						['Master', {{ $globalAvg['master'][$categories[$i]]}}, '#2F4F4F'],
						['Challenger', {{ $globalAvg['challenger'][$categories[$i]] }}, '#ffff00'],
					]);

					var {{ $categories[$i] }}_view = new google.visualization.DataView({{ $categories[$i]}});
					{{ $categories[$i] }}_view.setColumns([0, 1,
						{ calc: "stringify",
						  sourceColumn: 1,
						  type: "string",
						  role: "annotation" },
						  2]);
					
					var {{ $categories[$i] }}_options = {
						title: "Average {{ $categories[$i] }} In Game",
						width: 600,
						height: 400,
						bar: {groupWidth: "95%"},
						legend: { position: "none" },
					};
					var {{ $categories[$i] }}_chart = new google.visualization.ColumnChart(document.getElementById("{{ $categories[$i] }}_chart"));

					{{ $categories[$i] }}_chart.draw({{ $categories[$i] }}_view, {{ $categories[$i] }}_options);
				@endfor
			}
		</script>	
	</head>
	<body>
		@for($i = 0; $i < count($categories); $i++)
			<div id="{{ $categories[$i] }}_chart" style="width: 900px; height: 300px;"></div>
		@endfor
	
		<p><?php echo '<pre>'; print_r($summonerAvg); echo '</pre>'; ?></p>
		<p><?php echo '<pre>'; print_r($globalAvg); echo '</pre>';?></p>
	</body>
</html>
