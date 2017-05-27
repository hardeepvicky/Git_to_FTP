/**
 * @author     Hardeep
 */
$(document).ready(function()
{
    $("form").find("div.error-message").parents(".form-group").addClass("has-error"); 
   
    $("input[type='checkbox'].chk-select-all").chkSelectAll();

    $(".css-toggler").cssToggler();
    
    $(".ajax-loader").ajaxLoader();

    $(".more-text").moreText();
    
    $(".data-table").DataTable( {
        colReorder: true,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    });
});