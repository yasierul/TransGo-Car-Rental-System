// assets/js/main.js
// simple responsive menu (optional)
document.addEventListener('DOMContentLoaded', function(){
  const menu = document.querySelector('#menu-icon');
  const navbar = document.querySelector('.navbar');
  if (menu && navbar) {
    menu.onclick = () => {
      menu.classList.toggle('bx-x');
      navbar.classList.toggle('active');
    };
  }

  // small additional UX: highlight active nav on scroll (optional)
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.navbar a');
  function onScroll(){
    const scrollY = window.pageYOffset;
    sections.forEach(section => {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - 140;
      const sectionId = section.getAttribute('id');
      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        navLinks.forEach(link => link.classList.remove('active-link'));
        const activeLink = document.querySelector('.navbar a[href="#' + sectionId + '"]');
        if (activeLink) activeLink.classList.add('active-link');
      }
    })
  }
  window.addEventListener('scroll', onScroll);
});
