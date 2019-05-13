$(document).ready(function() {
  $("#myTextField").on("keyup", function() {
    // everytime keyup event
    var input = $(this).val(); // We take the input value
    if (input.length > 0) {
      // Minimum characters = 2 (you can change)
      var data = { input: input }; // We pass input argument in Ajax
      $.ajax({
        type: "POST",
        url: ROOT_URL + "autocomplete", // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
        data: data, // Send dataFields var
        dataType: "json", // json method
        timeout: 3000,
        success: function(response) {
          // If success
          $("#match").html(response.storyList); // Return data (UL list) and insert it in the <div id="match"></div>
          $(".story").css("display", "none");
          $(".westoryBtn").css("display", "none");
          $("#pagination").css("display", "none");
        },
        error: function() {
          // if error
          $("#match").text("Aucune suggestion.");
        }
      });
    } else {
      $("#match").text("");
      $(".story").css("display", "");
      $(".westoryBtn").css("display", "");
      $("#pagination").css("display", "");
    }
  });
});
