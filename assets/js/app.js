// ============================================
// assets/js/app.js — Global Utilities Planora
// ============================================

// Base URL ke folder api/
// Karena pages ada di /app/ dan api ada di /api/, path relatif dari HTML adalah ../api
const API_BASE = '../api';

// ---- Auth Helper ----
const Auth = {
    getUser() {
        const raw = localStorage.getItem('planoraUser');
        return raw ? JSON.parse(raw) : null;
    },
    setUser(user) {
        localStorage.setItem('planoraUser', JSON.stringify(user));
    },
    logout() {
        localStorage.removeItem('planoraUser');
        window.location.href = 'login.html';
    },
    requireLogin() {
        if (!this.getUser()) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    }
};

// ---- Alert Helper ----
function showAlert(containerId, message, type = 'danger') {
    const el = document.getElementById(containerId);
    if (!el) return;
    el.innerHTML = `
        <div class="alert alert-${type} alert-dismissible py-2 small fade show" role="alert">
            ${message}
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>`;
}

// ---- Fetch Wrapper ----
async function apiFetch(endpoint, options = {}) {
    const url = `${API_BASE}/${endpoint}`;
    const defaultOpts = {
        headers: { 'Content-Type': 'application/json' },
    };
    const res = await fetch(url, { ...defaultOpts, ...options });
    return res.json();
}
