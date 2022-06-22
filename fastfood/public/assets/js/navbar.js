var menuBar = document.querySelector("#menu-bar")
var navbar = document.querySelector(".navbar")



menuBar.onclick = ('click', () => {
    menuBar.classList.toggle('fa-times')
    navbar.classList.toggle('active')
})