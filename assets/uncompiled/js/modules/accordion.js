/*
* --------------------------------------------------
* Accordion
*
* Use [data-toggle="NAME"] or [data-show="NAME"] on a button
* Use [data-target="NAME"] on dropdown element
* Use [data-group="GROUP_NAME"] on element with [data-target] or [data-show] to assign a group name. Assigning group names will allow one accordion element to be open at a time.
*
* Add 'active' class to default dropdown element (element that has data-target)
* --------------------------------------------------
*/


// * hide all data targets/dropdowns by default
$('[data-target]:not(.active)').hide();

// * find target on click
$('[data-toggle]').on('click keypress', function(){

    // * toggle class for clicked element
    if( $(this).hasClass('active') ) {
        $(this).removeClass('active');
    } else {
        $('[data-toggle]').removeClass('active');
        $(this).addClass('active');
    }

    // * get target element
    let target = $(this).data('toggle');
    $('[data-target]').removeClass('active');
    $('[data-target='+ target +']').addClass('active');

    // * get target element's group if it exists
    let group = $('[data-target='+ target +']').data('group');

    // * hide all group items EXCEPT the current data target element
    $('[data-group]').not($('[data-target='+ target +']')).hide();

    // * toggle current data target element
    $('[data-target='+ target +']').toggle();
    
});

// * no toggle
$('[data-show]').on('click keypress', function(){

    // * toggle class for clicked element
    if( $(this).hasClass('active') ) {
        $(this).removeClass('active');
    } else {
        $('[data-show]').removeClass('active');
        $(this).addClass('active');
    }

    // * get target element
    let target = $(this).data('show');
    $('[data-target]').removeClass('active');
    $('[data-target='+ target +']').addClass('active');

    // * get target element's group if it exists
    let group = $('[data-target='+ target +']').data('group');

    // * hide all group items EXCEPT the current data target element
    $('[data-group]').not($('[data-target='+ target +']')).hide();

    // * toggle current data target element
    $('[data-target='+ target +']').show();
    
});
