class MyHeader extends HTMLElement {
    connectedCallback() {
        const title = this.getAttribute('title') || 'главное окно';
        const linkText = this.getAttribute('link-text') || '';
        const linkUrl = this.getAttribute('link-url') || '';
        
        const isLoggedIn = document.body.dataset.loggedIn === 'true';
        const userLogin = document.body.dataset.userLogin || '';
        
        let authButtonsHtml = '';
        if (!isLoggedIn) {
            authButtonsHtml = `
                <div class="auth-buttons">
                    <a href="login.php" class="login-btn">
                        <img src="../images/ui/lo.png" alt="вход">
                    </a>
                    <a href="register.php" class="register-btn">
                        <img src="../images/ui/registr_btn.png" alt="регистрация">
                    </a>
                </div>
            `;
        } else {
            authButtonsHtml = `
                <div class="auth-buttons">
                    <a href="#" class="logout-btn" id="logout-btn">
                        <img src="../images/ui/logout.png" alt="выйти">
                    </a>
                </div>
            `;
        }
        
        this.innerHTML = `
            <header class="header">
                <div class="header-wrapper">
                    <div class="header-left">
                        <div class="logo-img">
                            <img src="../images/ui/logo.png" alt="logo">
                        </div>
                        <div class="up-title">
                            ${linkUrl ? `<a href="${linkUrl}"><p>${linkText}</p></a>` : `<p>${title}</p>`}
                        </div>
                    </div>
                    ${authButtonsHtml}
                </div>
            </header>
        `;
        if (isLoggedIn) {
            const logoutBtn = this.querySelector('#logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLogoutConfirm(userLogin);
                });
            }
        }
    }
}

function showLogoutConfirm(userLogin) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    const modal = document.createElement('div');
    modal.className = 'logout-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <p class="modal-text">Вы уверены, что хотите выйти из аккаунта <strong>${userLogin}</strong>?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-yes">Да, выйти</button>
                <button class="modal-btn modal-btn-no">Отмена</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.appendChild(modal);
    modal.querySelector('.modal-btn-yes').addEventListener('click', function() {
        window.location.href = 'logout.php';
    });
    modal.querySelector('.modal-btn-no').addEventListener('click', function() {
        overlay.remove();
        modal.remove();
    });
    overlay.addEventListener('click', function() {
        overlay.remove();
        modal.remove();
    });
}

customElements.define('my-header', MyHeader);