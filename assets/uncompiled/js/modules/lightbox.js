/* 
* ------------------------------------------------------------------------------------------
* USAGE:
*
* <button data-lightbox-open="exampleLightbox">Example Lightbox</button>
* <div class="lightbox" id="exampleLightbox" tabindex="-1" role="dialog" aria-labelledby="exampleLightboxLabel" aria-hidden="true">
*   <div role="document">
*       <h2 id="exampleLightboxLabel">Testing Lightbox!</h2>
*   </div>
*   <button data-lightbox-close title="Close" aria-label="Close">&times;</button>
* </div>
* ------------------------------------------------------------------------------------------
*/

const lightbox = {
    open: () => {
        let lightbox_open = document.querySelectorAll('[data-lightbox-open');
        for (var i = 0; i < lightbox_open.length; i++) {
            lightbox_open[i].addEventListener('click', (event) => {
                event.preventDefault();
                // get lightbox id
                let id = event.target.attributes[0].value; 
                if( id !== "" ) { 
                    let element = document.getElementById(id); // lightbox id
                    element.classList.add('active'); // remove active class
                } else {
                    console.log("data-lightbox-open needs a value");
                }
            }); 
        }
    }, 
    close: () => {
        let lightbox_close = document.querySelectorAll('[data-lightbox-close');
        for (var i = 0; i < lightbox_close.length; i++) {
            lightbox_close[i].addEventListener('click', (event) => {
                event.preventDefault();
                // get lightbox id
                let id = event.target.attributes[0].value; 
                if( id !== "" ) { 
                    let element = document.getElementById(id); // get lightbox id
                    element.classList.remove('active'); // remove active class
                } else {
                    let element = event.target.parentElement; // clicked element's parent
                    element.classList.remove('active'); // remove active class
                }
            });
        }
    }
}

lightbox.open();
lightbox.close();