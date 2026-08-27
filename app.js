const toast = document.querySelector('#toast');
const input = document.querySelector('#command-input');
const feedback = document.querySelector('#feedback');

function showToast(message) {
  toast.textContent = message;
  toast.classList.add('show');
  window.setTimeout(() => toast.classList.remove('show'), 2600);
}

document.querySelectorAll('[data-action]').forEach((button) => {
  button.addEventListener('click', () => {
    const isResume = button.dataset.action === 'resume';
    showToast(isResume ? 'Lab resumed. Your workspace is ready.' : 'Lab added to your practice queue.');
    if (isResume) {
      document.querySelector('#activity').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
});

document.querySelector('#check-command').addEventListener('click', () => {
  const answer = input.value.trim().replace(/\s+/g, ' ');
  if (answer === 'git log --oneline -5' || answer === 'git log -5 --oneline') {
    feedback.textContent = 'Correct. Five compact commits, coming right up.';
    feedback.classList.remove('error');
    showToast('Nice work. Command accepted.');
  } else {
    feedback.textContent = 'Not quite. Hint: combine git log, --oneline, and -5.';
    feedback.classList.add('error');
  }
});

input.addEventListener('keydown', (event) => {
  if (event.key === 'Enter') document.querySelector('#check-command').click();
});

document.querySelector('.mobile-menu').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('open');
});

document.querySelectorAll('.nav-link').forEach((link) => {
  link.addEventListener('click', () => {
    document.querySelectorAll('.nav-link').forEach((item) => item.classList.remove('active'));
    link.classList.add('active');
    document.querySelector('.sidebar').classList.remove('open');
  });
});

document.querySelectorAll('.round-button').forEach((button) => {
  button.addEventListener('click', () => {
    document.querySelectorAll('.round-button').forEach((item) => item.classList.remove('active'));
    button.classList.add('active');
    showToast('Roadmap view updated.');
  });
});