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
 * @param div_id -> The ID of the div we are to be modifying
 * @param type -> passed in from 'reps.html.twig' or 'topcontent.html.twig tells us which boxes to check
 */
function rep_select(div_id, type){
  if(type == "all"){
    $(div_id).find(':checkbox').each(function () {
      $(this).attr('checked', true);
    });
  }
  else{
    rep_deselect(div_id);
    if(type == "Rep"){
      $(div_id).find(':checkbox').each(function () {
        $(div_id + ' input[data-emp-type="Rep"]').attr('checked', true);
      });
    }
    else if(type == "Agent"){
      $(div_id).find(':checkbox').each(function () {
        $(div_id + ' input[data-emp-type="Agent"]').attr('checked', true);
      });
    }
  }
}

/**
 * Deselect all checkboxes in the 'rep-list'
 *
 * Called if 'Rep' or 'Agent' Select All's are requested
 */
function rep_deselect(div_id){
  $(div_id).find(':checkbox').each(function () {
    $(this).attr('checked', false);
  });
}

/**
 * Populates the Date Selection boxes
 *
 * @param dateArray -> [0]Start Months [1]Start Years [2]End Months [3]End Years
 */
function date_populate(div_id, dateArray){
  for(var x=0; x<dateArray.length; x++){
    for(var y=0; y<dateArray[x].length; y++){
      var val = '<option value="' + dateArray[x][y] + '">' + dateArray[x][y] + '</option>';
      if(div_id == '#dateSelect'){
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
      else{
        if(x == 0){
          $("#dateCCSM").append(val);
        }
        else if(x == 1){
          $("#dateCCSY").append(val);
        }
        else if(x == 2){
          $("#dateCCEM").append(val);
          $("#dateCEM").append(val);
          $("#dateCGM").append(val);
          $("#dateCBM").append(val);
        }
        else if(x == 3){
          $("#dateCCEY").append(val);
          $("#dateCEY").append(val);
          $("#dateCGY").append(val);
          $("#dateCBY").append(val);
        }
      }
    }
  }
}

function generate_tabs(div_id){
  $( function() {
    $( div_id ).tabs();
  });
}