function toggleMenu() {
    var botao_menu = document.querySelector('.botao-menu');
    var menu = document.querySelector('.menu-hamburger');

    if (botao_menu.classList.contains('mostrar')) {
        menu.remove.toggle('mostrar');
    }else{
        menu.classList.toggle('mostrar');
    }
}
