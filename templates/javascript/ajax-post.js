/**
 * Handles AJAX calls to generate reports
 *
 * Created by alex on 9/26/16.
 */
function reportCall(report){
    //TODO: Ensure all inputs are validated, otherwise return an error
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { report: report }
    });
}
