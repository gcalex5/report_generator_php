/**
 * JavaScript utility functions are placed here
 * Currently
 * 1: rep_select -> checks requested boxes on the options menu
 * 2: rep_deselect -> deselects all checkboxes when called
 * 3: date
 *
 * Created by alex on 9/28/16.
 */

/**
 * Check the requested check boxes
 *
 * @param type -> passed in from 'reps.html.twig' tells us which boxes to check
 */
function rep_select(type){
  if(type == "all"){
    $('#rep-list').find(':checkbox').each(function () {
      jQuery(this).attr('checked', true);
    });
  }
  else{
    rep_deselect();
    if(type == "Rep"){
      $('#rep-list').find(':checkbox').each(function () {
        $('input[data-emp-type="Rep"]').attr('checked', true);
      });
    }
    else if(type == "Agent"){
      $('#rep-list').find(':checkbox').each(function () {
        $('input[data-emp-type="Agent"]').attr('checked', true);
      });
    }
  }
}

/**
 * Deselect all checkboxes in the 'rep-list'
 *
 * Called if 'Rep' or 'Agent' Select All's are requested
 */
function rep_deselect(){
  $('#rep-list').find(':checkbox').each(function () {
    $(this).attr('checked', false);
  });
}

/**
 * Populates the Date Selection boxes
 *
 * @param dateArray -> [0]Start Months [1]Start Years [2]End Months [3]End Years
 */
function date_populate(dateArray){
  for(var x=0; x<dateArray.length; x++){
    for(var y=0; y<dateArray[x].length; y++){
      var val = '<option value="' + dateArray[x][y] + '">' + dateArray[x][y] + '</option>';
      if(x == 0){
        $("#dateSM").append(val);
      }
      else if(x == 1){
        $("#dateSY").append(val);
      }
      else if(x == 2){
        $("#dateEM").append(val);
        $("#dateM").append(val);
      }
      else if(x == 3){
        $("#dateEY").append(val);
        $("#dateY").append(val);
      }
    }
  }
}

function generate_tabs(div_id){
  $( function() {
    $( div_id ).tabs();
  });
}