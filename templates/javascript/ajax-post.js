/**
 * Handles AJAX calls to generate reports
 *
 * Created by alex on 9/26/16.
 */
function reportCall(report){
    //TODO: Ensure all inputs are validated, otherwise return an error
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
        data: { report: report, dateSM: dateSM, dateSY: dateSY, dateEM: dateEM,
            dateEY: dateEY, empIDS: empIDS  }
    });
}
