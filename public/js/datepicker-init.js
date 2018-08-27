$(document).ready(function() {
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    }).each(function (i, obj) {
        if($(obj).val() === '') {  // If datepicker input is empty, set its value to current date
            $(obj).datepicker("setDate",'now');
        }
    });
});
