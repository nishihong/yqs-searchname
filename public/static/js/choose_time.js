$(document).ready(function(){
	$('.o_day').click(function(){
	// alert(2)
	    var day     = $(this).attr('data-time');
	    var time    = Date.parse(new Date());
	    var fmt     = $(this).attr('data-fmt');
	    if(day == 0){
	        time = time-(86400*day*1000);
	        var newDate = new Date(time);
	        var time1 = formatDate(newDate,1);
	        var time2 = formatDate(newDate,1);

	    }else if(day == 1){
	        time = time-(86400*day*1000);
	        var newDate = new Date(time);
	        var time1 = formatDate(newDate,1);
	        var time2 = formatDate(newDate,1);
	    }else if(day == 30){
	        var newDate = new Date(time);
	        var time1 = formatDate(newDate,2);
	        var time2 = formatDate(newDate,1);
	    }else if(day == -7){
	        var time1 = getLastWeekStartDate();
	        var time2 = getLastWeekEndDate();
	    }else if(day == 7){
	        time1 = time-(86400*day*1000);
	        time2 = time;
	        var newDate1 = new Date(time1);
	        var newDate2 = new Date(time2);
	        var time1 = formatDate(newDate1,1);
	        var time2 = formatDate(newDate2,1);
	    }else if(day == -30){
	        var time1 = getLastMonthStartDate();
	        var time2 = getLastMonthEndDate();
	    }
	    if(fmt=='hi'){
	        time1 = time1;
	        time2 = time2;
	    }
	    $("#start_time").val(time1);
	    $("#end_time").val(time2);

	    // $("#start_time").siblings('.prompt-desc').hide();
	    // $("#end_time").siblings('.prompt-desc').hide();

	});
});