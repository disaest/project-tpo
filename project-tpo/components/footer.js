class MyFooter extends HTMLElement {
    connectedCallback() {
    this.innerHTML = `
        <footer class="footer">
            <div class="logo-img">
                <img src="images/foot_l.png"</img>
            </div>
            <div class="title">
                <p>COOK GO!</p>
                <p>Готовьте с нами</p>
            </div>
        </footer> `;
    }
}
customElements.define('my-footer', MyFooter)