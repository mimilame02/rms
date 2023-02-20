$('#calendar').fullCalendar({
    // other options...
    eventClick: function(calEvent, jsEvent, view) {
      // Handle event click here...
    }
  });

  $('#add-event-btn').click(function() {
    // Prompt the user for the event title and start/end dates/times
    var title = prompt("Event Title:");
    var start = moment(prompt("Start Date/Time (YYYY-MM-DD HH:mm):"));
    var end = moment(prompt("End Date/Time (YYYY-MM-DD HH:mm):"));
  
    // Create a new event object
    var event = {
      title: title,
      start: start,
      end: end
    };
  
    // Add the event to the calendar
    $('#calendar').fullCalendar('renderEvent', event, true);
  });
  