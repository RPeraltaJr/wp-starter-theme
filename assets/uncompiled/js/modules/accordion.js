/*
* --------------------------------------------------
* Accordion
*
* Use [data-toggle="#element"] on a button
* Use [data-target="#element"] on dropdown element
* Use [data-group="#group-name"] on element with [data-target] to assign a group name. Assigning group names will allow one accordion element to be open at a time.
* --------------------------------------------------
*/

// hide all data targets/dropdowns by default
$('[data-target]').hide();

// find target on click
$('[data-toggle]').on('click keypress', function(){

    // toggle class for clicked element
    if( $(this).hasClass('data-toggle-active') ) {
        $(this).removeClass('data-toggle-active');
    } else {
        $('[data-toggle]').removeClass('data-toggle-active');
        $(this).addClass('data-toggle-active');
    }

    // get target element
    var target = $(this).data('toggle');

    // get target element's group if it exists
    var group = $('[data-target='+ target +']').data('group');

    // hide all group items EXCEPT the current data target element
    $('[data-group]').not($('[data-target='+ target +']')).slideUp();

    // toggle current data target element
    $('[data-target='+ target +']').slideToggle();
    
});
