// -------------------
// Turnstile helper functions
// -------------------

(function(){

    const navMenuBtns = document.querySelectorAll('.nav-menu__btn');
    const navMenus = document.querySelectorAll('.nav-menu');
    
    // track cliks on menu
    navMenuBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            btn.classList.toggle('open');
        });
    });
    
    // listener to close menu if clicking outside
    document.addEventListener('click', function(event) {
        if (!(
            Array.from(navMenuBtns).includes(event.target)
            || Array.from(navMenus).includes(event.target)
        )) {
            navMenuBtns.forEach(btn => btn.classList.remove('open'));
        }
    });
   
})();