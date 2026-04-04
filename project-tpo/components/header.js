class MyHeader extends HTMLElement {
    connectedCallback() {
    this.innerHTML = `
        <header class="header">
            <div class="logo-img">
                <img src="images/logo.png" margin-left="20" width ="126.52" height="130.11"</img>
            </div>
            <div class="title">
                <p>категории</p>
            </div>
        </header> `;
    }
}
customElements.define('my-header', MyHeader)