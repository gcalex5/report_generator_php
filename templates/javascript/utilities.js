/**
 * JavaScript utility functions are placed here
 * Currently
 * 1: rep_select -> checks requested boxes on the options menu
 * 2: rep_deselect -> deselects all checkboxes when called
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
        else {
            //TODO: Put some code here or get rid of it
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
        jQuery(this).attr('checked', false);
    });
}