	$(document).on("click",".order-create",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				variety_id: my_id
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "order/create",
			success: function(data){
				show_popup("New Order",data, "auto");
				//$(".all-orders").append(data);
				//$(".order-create").fadeOut();
			}
		});
		
	});
	
	$(document).on("change","input[name='flat_size'],input[name='flat_cost'],input[name='plant_cost']",function(){
		flat_size = $("input[name='flat_size']").val();
		flat_cost = $("input[name='flat_cost']").val();
		plant_cost = $("input[name='plant_cost']").val();
		if(flat_cost && !plant_cost){
			$("input[name='plant_cost']").val( parseFloat(flat_cost) / parseFloat(flat_size) );
		}else if(!flat_cost && plant_cost){
			$("input[name='flat_cost']").val(parseFloat(flat_size) * parseFloat(plant_cost));
		}
		console.log(flat_cost);
	});
	
	$(document).on("click",".search-orders",function(){
		form_data = {
				find: 1
		};
		$.ajax({
			type: "get",
			data: form_data,
			url: base_url + "order/search",
			success: function(data){
				show_popup("Search Orders",data, "auto");
			}
		});
	});
	
	$(document).on("click",".edit-order",function(){
		my_id = this.id.split("_")[1];
		form_data = {
				id: my_id,
				ajax: 1
		};
		$.ajax({
			type:"get",
			data: form_data,
			url: base_url + "order/edit/" + my_id,
			success: function(data){
				show_popup("Editing an Order",data,"auto");
			}
		});
	});
	
	
	$(document).on("click",".show-order-totals",function(){
		$("#order-totals").html("Loading...");
			
		$.ajax({
			type: "get",
			url: base_url + "index/get_order_totals",
			success: function(data){
				$("#order-totals").html(data);
			}
		});
	});
	
	$(document).on("click",".add-order-sort", function(){
		$(this).fadeOut();
		$.ajax({
			type:"get",
			url: base_url + "order/show_sort",
			success: function(data){
				$("#sort-block").append(data);
			}
		});
		
	});
	
	$(document).on("click",".delete-order",function(){
		question = confirm("Are you sure you want to delete this order? This cannot be undone!");
		if(question){
			again = confirm("Are you really sure you want to delete this? Click cancel if you aren't sure.");
			if(again){
				my_id = this.id.split("_")[1];
			
				form_data = {
						id: my_id,
						ajax: 1
				};
				$.ajax({
					type:"post",
					url: base_url + "order/delete",
					data: form_data,
					success: function(data){
						document.location = base_url + "variety/view/" + data;
					}
				});
			}
		}
	});
	
