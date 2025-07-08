// Starry background effect
window.addEventListener('DOMContentLoaded', () => {
  const bg = document.createElement('div');
  bg.className = 'starry-bg';
  document.body.appendChild(bg);
  for (let i = 0; i < 80; i++) {
    const star = document.createElement('div');
    star.className = 'star';
    star.style.top = Math.random() * 100 + 'vh';
    star.style.left = Math.random() * 100 + 'vw';
    const size = Math.random() * 2 + 1;
    star.style.width = size + 'px';
    star.style.height = size + 'px';
    star.style.animationDuration = (Math.random() * 2 + 1) + 's';
    bg.appendChild(star);
  }
}); 