// public/assets/js/curtain.js
document.addEventListener('click', () => {
  const c = document.querySelector('.curtain');
  if (!c.classList.contains('open')) {
    c.classList.add('open');
    const audio = document.getElementById('curtain-audio');
    if (audio) { audio.volume = 0.6; audio.play().catch(()=>{}); }
    setTimeout(() => {
      window.location.href = 'home.php';
    }, 1700);
  }
});
