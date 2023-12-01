function onrequired(){
    console.log($(container).find('form'));
}
onrequired();


// var selectedoptions =[];


// var desactiverSelect=()=>{

//     $(container).find('select.banque').on('change', function(){
//         selectedoptions[$(this).val()] = $(this).val();
//         console.log(selectedoptions);
//     });

//     var mesoption=[];
//     for(var key in selectedoptions) {
//         mesoption.push(selectedoptions[key]);
//     }
    
//     // console.log(mesoption);
//     $.each($(container).find('select.banque'), function (i, elt) {
//         $.each($(elt).find('option:not(:selected,:disabled)'), function(i, item) {
//             if ($.inArray($(this).val() , mesoption) != -1) {
//                 $(item).prop("disabled", true);
//             } else {
//                 $(item).prop("disabled", false);
//             }
//         });
//     });

// }
