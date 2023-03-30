// ============
// THEME TOGGLE
// ============

const darkToggle = document.getElementById('themeToggler');
const body = document.body;

darkToggle.addEventListener('click', () => {
    body.classList.toggle('dark');
});

const theme = localStorage.getItem('theme');
if (theme === 'dark') {
    body.classList.add('dark');
} else {
    body.classList.remove('dark');
}

