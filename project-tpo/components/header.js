class MyHeader extends HTMLElement {
    connectedCallback() {
    this.innerHTML = `
        <header class="header">
            <div class="logo-img">
                <img src="images/logo.png"</img>
            </div>
            <div class="title">
                <p>категории</p>
            </div>
        </header> `;
    }
}
customElements.define('my-header', MyHeader)