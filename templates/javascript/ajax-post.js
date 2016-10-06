/**
 * Handles AJAX calls to generate reports
 *
 * Created by alex on 9/26/16.
 */
function reportCall(report){
  //TODO: Ensure all inputs are validated, otherwise return an error
  var dateM = $( "#dateM" ).val();
  var dateY = $( "#dateY" ).val();
  var dateSM = $( "#dateSM" ).val();
  var dateSY = $( "#dateSY" ).val();
  var dateEM = $( "#dateEM" ).val();
  var dateEY = $( "#dateEY" ).val();

  var empIDS = [];
  var x = 0;
  $('#rep-list').find(':checkbox').each(function () {
    var id = (this.checked ? $(this).val() : "");
    if (id != null && id > 0){
      empIDS[x] = id;
      x++;
    }
  });

  $.ajax({
    type: "POST",
    url: "index.php",
    data: { report: report, dateM: dateM, dateY: dateY, dateSM: dateSM, dateSY: dateSY, dateEM: dateEM,
      dateEY: dateEY, empIDS: empIDS, partial: 'partial'  },
    success: function(response) {
      $('#content').html(response);
    }
  });
}

function comboReportCall(){
  //TODO: Ensure all inputs are validated, otherwise return an error
  var dateCEM = $( "#dateCEM" ).val();
  var dateCEY = $( "#dateCEY" ).val();
  var dateCGM = $( "#dateCGM" ).val();
  var dateCGY = $( "#dateCGY" ).val();
  var dateCBM = $( "#dateCBM" ).val();
  var dateCBY = $( "#dateCBY" ).val();
  var dateCCSM = $( "#dateCCSM" ).val();
  var dateCCSY = $( "#dateCCSY" ).val();
  var dateCCEM = $( "#dateCCEM" ).val();
  var dateCCEY = $( "#dateCCEY" ).val();

  var empIDS = [];
  var x = 0;
  $('#combo-rep-list').find(':checkbox').each(function () {
    var id = (this.checked ? $(this).val() : "");
    if (id != null && id > 0){
      empIDS[x] = id;
      x++;
    }
  });

  $.ajax({
    type: "POST",
    url: "index.php",
    data: { report: 'combo', dateCEM: dateCEM, dateCEY: dateCEY, dateCGM: dateCGM, dateCGY: dateCGY, dateCBM: dateCBM, dateCBY: dateCBY,
      dateCCSM: dateCCSM, dateCCSY: dateCCSY, dateCCEM: dateCCEM, dateCCEY: dateCCEY, empIDS: empIDS, partial: 'partial'
    },
    success: function(response) {
      $('#content').html(response);
    }
  });
}
