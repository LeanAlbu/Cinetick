import { setupAuthUI } from './common/auth.js';
import { initializeCarousels, initializeLoginModal } from './common/ui.js';

document.addEventListener('DOMContentLoaded', () => {
    setupAuthUI();
    initializeLoginModal();
    initializeCarousels();
});
