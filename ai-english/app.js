const authButton = document.querySelector('#authButton');
const authButtonText = document.querySelector('#authButtonText');
const authNote = document.querySelector('#authNote');
const profileLabel = document.querySelector('#profileLabel');
const avatar = document.querySelector('#avatar');
const authModal = document.querySelector('#authModal');
const authForm = document.querySelector('#authForm');
const authTitle = document.querySelector('#authTitle');
const authSubmitText = document.querySelector('#authSubmitText');
const profileFields = document.querySelector('#profileFields');
const practiceModal = document.querySelector('#practiceModal');
const modalTitle = document.querySelector('#modalTitle');
const closeModal = document.querySelector('#closeModal');
const recordButton = document.querySelector('#recordButton');
const recordLabel = document.querySelector('#recordLabel');
const recordStatus = document.querySelector('#recordStatus');
const conversation = document.querySelector('#conversation');
const toast = document.querySelector('#toast');
const minutesValue = document.querySelector('.minutes strong');
const signinLink = document.querySelector('#signinLink');
const coachTitle = document.querySelector('#coachTitle');
const coachSummary = document.querySelector('#coachSummary');
const accountMenu = document.querySelector('#accountMenu');
const profileButton = document.querySelector('#profileButton');
const selectedPlan = document.querySelector('#selectedPlan');
let authMode = 'signup';
let recognition;
let toastTimer;

function showToast(message) {
  toast.textContent = message;
  toast.classList.add('visible');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove('visible'), 2800);
}

function getAccount() {
  return JSON.parse(localStorage.getItem('morrowAccount') || 'null');
}

function setProfile(profile) {
  if (profile) {
    localStorage.setItem('morrowAccount', JSON.stringify(profile));
    authButtonText.textContent = 'Your learning space is ready';
    authNote.textContent = `Signed in as ${profile.userId}`;
    profileLabel.textContent = profile.name.split(' ')[0];
    avatar.textContent = profile.name[0].toUpperCase();
    signinLink.style.display = 'none';
    coachTitle.textContent = `A path made for ${profile.name.split(' ')[0]}`;
    coachSummary.innerHTML = `<strong>${profile.level}</strong> learner · Focus: <strong>${profile.goal}</strong>${profile.interests ? `<br />Topics: ${profile.interests}` : ''}`;
    selectedPlan.textContent = profile.plan ? `${profile.plan} plan selected · ${profile.planDetail}` : 'Choose a plan below to set your weekly rhythm.';
  } else {
    localStorage.removeItem('morrowAccount');
    authButtonText.textContent = 'Create your account';
    authNote.textContent = 'Your account and progress stay on this device';
    profileLabel.textContent = 'Guest';
    avatar.textContent = 'G';
    signinLink.style.display = 'block';
    coachTitle.textContent = 'Make your practice personal';
    coachSummary.textContent = 'Create an account so your coach can remember your level, goals, and favorite topics.';
    selectedPlan.textContent = 'Sign in to save your learning plan.';
  }
}

function openAuth(mode) {
  authMode = mode;
  authTitle.textContent = mode === 'signup' ? 'Create your account' : 'Welcome back';
  authSubmitText.textContent = mode === 'signup' ? 'Create account' : 'Sign in';
  profileFields.style.display = mode === 'signup' ? 'block' : 'none';
  document.querySelector('#nameInput').required = mode === 'signup';
  document.querySelector('#nameInput').parentElement.style.display = mode === 'signup' ? 'grid' : 'none';
  document.querySelectorAll('.auth-tab').forEach((tab) => tab.classList.toggle('active', tab.dataset.authMode === mode));
  authModal.classList.add('open');
  authModal.setAttribute('aria-hidden', 'false');
}

setProfile(getAccount());
authButton.addEventListener('click', () => getAccount() ? (setProfile(null), showToast('You have been signed out.')) : openAuth('signup'));
profileButton.addEventListener('click', () => { if (!getAccount()) { openAuth('signin'); return; } const isOpen = accountMenu.classList.toggle('open'); profileButton.setAttribute('aria-expanded', String(isOpen)); });
document.querySelector('#logoutButton').addEventListener('click', () => { setProfile(null); accountMenu.classList.remove('open'); profileButton.setAttribute('aria-expanded', 'false'); showToast('You have been signed out.'); });
document.querySelector('#menuProfile').addEventListener('click', () => { accountMenu.classList.remove('open'); document.querySelector('#lessons').scrollIntoView({ behavior: 'smooth' }); });
document.addEventListener('click', (event) => { if (!event.target.closest('.profile-button') && !event.target.closest('.account-menu')) { accountMenu.classList.remove('open'); profileButton.setAttribute('aria-expanded', 'false'); } });
document.querySelector('#signinLink').addEventListener('click', () => openAuth('signin'));
document.querySelectorAll('.auth-tab').forEach((tab) => tab.addEventListener('click', () => openAuth(tab.dataset.authMode)));
document.querySelector('#closeAuth').addEventListener('click', () => { authModal.classList.remove('open'); authModal.setAttribute('aria-hidden', 'true'); });

authForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const userId = document.querySelector('#userIdInput').value.trim().toLowerCase();
  const password = document.querySelector('#passwordInput').value;
  const accounts = JSON.parse(localStorage.getItem('morrowAccounts') || '{}');
  if (authMode === 'signup') {
    if (accounts[userId]) { showToast('That user ID already exists. Try signing in.'); return; }
    accounts[userId] = { userId, password, name: document.querySelector('#nameInput').value.trim(), level: document.querySelector('#levelInput').value, language: document.querySelector('#languageInput').value.trim(), goal: document.querySelector('#goalInput').value, interests: document.querySelector('#interestsInput').value.trim(), corrections: document.querySelector('#correctionInput').checked };
    localStorage.setItem('morrowAccounts', JSON.stringify(accounts));
    setProfile(accounts[userId]);
    showToast('Account created. Your coach knows what matters to you.');
  } else {
    if (!accounts[userId] || accounts[userId].password !== password) { showToast('User ID or password is incorrect.'); return; }
    setProfile(accounts[userId]);
    showToast(`Welcome back, ${accounts[userId].name.split(' ')[0]}.`);
  }
  authModal.classList.remove('open');
  authModal.setAttribute('aria-hidden', 'true');
  authForm.reset();
});

document.querySelectorAll('.plan-card').forEach((card) => card.addEventListener('click', () => {
  const profile = getAccount();
  if (!profile) { openAuth('signup'); showToast('Create an account to save your plan.'); return; }
  profile.plan = card.dataset.plan;
  profile.planDetail = card.dataset.detail;
  setProfile(profile);
  document.querySelectorAll('.plan-card').forEach((item) => item.classList.toggle('selected', item === card));
  showToast(`${profile.plan} plan selected.`);
}));

function openPractice(topic) {
  modalTitle.textContent = topic;
  const profile = getAccount();
  const context = profile ? ` You are ${profile.level.toLowerCase()} and want to practice ${profile.goal.toLowerCase()}${profile.interests ? `, especially ${profile.interests}` : ''}${profile.plan ? ` Your ${profile.plan} plan is ${profile.planDetail.toLowerCase()}.` : ''}` : '';
  conversation.querySelector('.ai-message p').textContent = `Tell me one good thing about your day so far.${context}`;
  practiceModal.classList.add('open');
  practiceModal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
}

document.querySelectorAll('.practice-card').forEach((card) => card.addEventListener('click', () => openPractice(card.dataset.topic)));
document.querySelectorAll('.lesson-item').forEach((lesson) => lesson.addEventListener('click', () => openPractice(lesson.dataset.topic)));
document.querySelector('#randomPractice').addEventListener('click', () => { const cards = [...document.querySelectorAll('.practice-card')]; openPractice(cards[Math.floor(Math.random() * cards.length)].dataset.topic); });
function closePractice() { practiceModal.classList.remove('open'); practiceModal.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; if (recognition) recognition.stop(); recordButton.classList.remove('recording'); recordLabel.textContent = 'Tap to speak'; }
closeModal.addEventListener('click', closePractice);
practiceModal.addEventListener('click', (event) => { if (event.target === practiceModal) closePractice(); });
document.querySelector('#skipButton').addEventListener('click', () => showToast('No pressure. Try the next question when you are ready.'));
document.querySelector('#finishButton').addEventListener('click', () => { const minutes = Number(localStorage.getItem('morrowMinutes') || 12) + 5; localStorage.setItem('morrowMinutes', String(minutes)); minutesValue.textContent = minutes; closePractice(); showToast('Practice complete. +5 minutes added to your week.'); });
minutesValue.textContent = localStorage.getItem('morrowMinutes') || '12';

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
if (SpeechRecognition) {
  recognition = new SpeechRecognition();
  recognition.lang = 'en-US';
  recognition.interimResults = false;
  recognition.onstart = () => { recordButton.classList.add('recording'); recordLabel.textContent = 'Listening…'; recordStatus.textContent = 'Speak naturally, I am listening.'; };
  recognition.onend = () => { recordButton.classList.remove('recording'); recordLabel.textContent = 'Tap to speak'; };
  recognition.onerror = () => { recordButton.classList.remove('recording'); recordLabel.textContent = 'Tap to speak'; recordStatus.textContent = 'I could not hear that. Try once more.'; };
  recognition.onresult = (event) => { const transcript = event.results[0][0].transcript; const message = document.createElement('div'); message.className = 'message user-message'; message.innerHTML = `<p>${transcript}</p>`; conversation.appendChild(message); setTimeout(() => { const reply = document.createElement('div'); reply.className = 'message ai-message'; const replyText = getAccount()?.corrections ? 'Nice work. A more natural way to say that may be: “That sounds great.” What made it feel good?' : 'That sounds lovely. What made it feel good?'; reply.innerHTML = `<span class="message-avatar">m.</span><p>${replyText}</p>`; conversation.appendChild(reply); if ('speechSynthesis' in window) window.speechSynthesis.speak(new SpeechSynthesisUtterance(replyText)); recordStatus.textContent = 'Nice work. Keep the conversation going.'; }, 500); };
} else { recordStatus.textContent = 'Voice input is supported in Chrome and Edge.'; }
recordButton.addEventListener('click', () => { if (!recognition) { showToast('Try opening this page in Chrome or Edge for voice practice.'); return; } if (recordButton.classList.contains('recording')) recognition.stop(); else recognition.start(); });
document.addEventListener('keydown', (event) => { if (event.key === 'Escape') { closePractice(); authModal.classList.remove('open'); } });
