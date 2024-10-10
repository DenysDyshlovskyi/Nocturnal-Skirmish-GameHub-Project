//Prevents form resubmission on refresh of page
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
};