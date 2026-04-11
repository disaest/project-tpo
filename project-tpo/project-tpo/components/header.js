class MyHeader extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <header class="header">
            <div class="logo-img">
                <img src="images/logo.png">
            </div>
            <div class="up-title">
                <p>главное окно</p>
            </div>
            <div class="registr_btn">
                <img src="images/registr_btn.png">
            </div>
        </header>`;
    }
}
customElements.define('my-header', MyHeader);