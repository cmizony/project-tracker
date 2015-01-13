function plot_chart_activities(dom_selector,row_data)
{
	var width_dom = Math.max(400,$(dom_selector.svg).width());

	var margin = {top: 20, right: 0, bottom: 80, left: 40},
		 width = width_dom - margin.left - margin.right,
		 height = 350 - margin.top - margin.bottom;

	var x = d3.scale.ordinal()
		.rangeRoundBands([0, width], .1);

	var y = d3.scale.linear()
		.rangeRound([height, 0]);

	function hsl_col(ratio) {
		var start = 0, end = 120; 
		var a = ratio > 1 ? 1:ratio; // Green to Red
		var	b = end*a;
		var c = b+start;
		return 'hsl('+c+',100%,50%)';
	}

	var xAxis = d3.svg.axis()
		.scale(x)
		.orient("bottom");

	var yAxis = d3.svg.axis()
		.scale(y)
		.orient("left");

	$(dom_selector.svg).empty();

	var svg = d3.select(dom_selector.svg).append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
		.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	var div = d3.select(dom_selector.svg).append("div")   
		.attr("class", "tooltip")               
		.style("opacity", 0);


	var formatted_data = new Object();
	var meta_data_y = new Object();
	var x_domain = new Array();
	var y_max = 0;

	var group_by_x = "date";
	var format_group_by_x = function (value) {
		var moment_date = moment(value[group_by_x],"MMMM DD YYYY HH:mm a");
		return moment_date.format("YYYY-MM-DD");  
	};

	var group_by_y = $(dom_selector.select).val();

	/********************************/
	/*    DATA preparation BLOCK    */
	/********************************/

	row_data.forEach(function(entry){
		var i = format_group_by_x(entry);

		// STEP.1 Set name & Sum - O(n)
		if (formatted_data[i] === undefined)
			formatted_data[i] = {x_name:i,x_value:{}};

		if (formatted_data[i].x_value[entry[group_by_y]] === undefined)
			formatted_data[i].x_value[entry[group_by_y]] = { date:i,sum:0,y0:0,y1:0,name:entry[group_by_y] };
		formatted_data[i].x_value[entry[group_by_y]].sum += 1;

		// STEP.2.1 Get Meta-y & Sum 
		var index_y = entry[group_by_y];
		if (meta_data_y[index_y] === undefined)
			meta_data_y[index_y] = { name:index_y, sum:0};
		meta_data_y[index_y].sum += 1;
	});

	// STEP 2.2 Sort meta_y Object O(1) - Bubble sort
	var sorted_meta_y = new Object();
	var balance = { key:null, max:Number.MAX_VALUE, sum:-1, order:1 };
	var total = Object.keys(meta_data_y).length;
	for (var i = 0 ; i < total ; i ++)
	{
		for (j in meta_data_y)
			if (meta_data_y[j].sum >= balance.sum &&
				meta_data_y[j].sum <= balance.max)
			{
				balance.sum = meta_data_y[j].sum;
				balance.key =j;
			}

		sorted_meta_y[meta_data_y[balance.key].name]= meta_data_y[balance.key];
		sorted_meta_y[meta_data_y[balance.key].name].order = balance.order;
		
		balance.sum = 0;
		balance.order ++;
		balance.max = meta_data_y[balance.key].sum;

		delete meta_data_y[balance.key];
	}
	meta_data_y = sorted_meta_y;

	// STEP.3 Set y0 & y1 - O(n) & Set x_domain
	for (x_key in formatted_data) {
		var date_item = formatted_data[x_key];
		var bar_sum = 0;
		x_domain.push(date_item.x_name);
		
		for (meta_key in meta_data_y)
		{
			var y_name = meta_data_y[meta_key].name;
			var bar_item = date_item.x_value[y_name];

			if (bar_item === undefined)
				bar_item = formatted_data[x_key].x_value[y_name] = {date:i,sum:0,y0:0,y1:0,name:y_name };
			
			bar_item.y0 = bar_sum;
			bar_sum += bar_item.sum;
			bar_item.y1 = bar_sum;
		}
		y_max = Math.max(y_max,bar_sum);
	}

	// STEP.4 Convert Object to Array - O(n)
	formatted_data = $.map(formatted_data, function(entry) { 
		entry.x_value = $.map(entry.x_value, function(content) { return [content] }); 
		return [entry];
	});

	// STEP.5 Sort Date domain - O(1)
	x_domain.sort(function(a, b) { return a.localeCompare(b); }); // String

	/********************************/
	/*    DATA preparation BLOCK    */
	/********************************/

	x.domain(x_domain);
	y.domain([0, y_max]);

	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis)
		.selectAll("text")
		.style("text-anchor", "end")
		.attr("dx", "-.8em")
		.attr("dy", ".15em")
		.attr("transform", "rotate(-65)");

	svg.append("g")
		.attr("class", "y axis")
		.call(yAxis)
		.append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end")
		.text("Log entries");

	var bars_x = svg.selectAll(".date")
		.data(formatted_data)
		.enter().append("g")
		.attr("class", "g")
		.attr("transform", function(d) { return "translate(" + x(d.x_name) + ",0)"; });
		
	bars_x.selectAll("rect")
		.data(function(d) { return d.x_value })
		.enter().append("rect")
		.attr("width", x.rangeBand())
		.attr("y", function(d) { return y(d.y1); })
		.attr("height", function(d) { return y(d.y0) - y(d.y1); })
		.style("fill", function (d) {
			return hsl_col(meta_data_y[d.name].order / Math.min(Object.keys(meta_data_y).length,10));
		})
		.attr("data-legend",function(d) {
			if (meta_data_y[d.name].order > 9) return;
			return meta_data_y[d.name].order +
			". " + d.name + 
			" ("+ parseInt(meta_data_y[d.name].sum*100 / row_data.length) +"%)";
		})
		.style("fill-opacity","0.6")
		.on("mousemove", function(d) { 
			div.style("background","#049cdb");   
			div.style("border","1px solid #008080");   
			div.transition().duration(200).style("opacity", .8);
		
			var text = d.name + " - " + d.sum + " activities<br>on ";
			text +=  moment(d.date,"YYYY-MM-DD").format("ddd MMM D YYYY"); 
			div.html(text)
				.style("left", (event.pageX) + "px")
				.style("top", (event.pageY-40)  + "px");
		})
		.on("mouseout", function(d) {       
			div.transition()        
			.duration(800)      
			.style("opacity", 0);   
		});

		if (!d3.legend)
			initialize_d3_legend();

		legend = svg.append("g")
			.attr("class","legend")
			.attr("transform","translate(40,10)")
			.style("font-size","12px")
			.call(d3.legend);
}
