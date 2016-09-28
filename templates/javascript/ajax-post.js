/**
 * Handles AJAX calls to generate reports
 *
 * Created by alex on 9/26/16.
 */
function reportCall(report){
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { report: report }
    });
}
