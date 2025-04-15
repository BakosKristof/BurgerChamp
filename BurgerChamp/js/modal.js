class Modal {
    #modal = this.#getModal("", "");
    #container =   document.getElementById("modal-container");


    #getModal(title, content) {
        return `
         <div id="blur"> </div>
        <div id="modal"> 
            <div class="header">
                <h4>${title}</h4>
            </div>
            <div class="body">
                <p>${content}</p>
             </div>
            <div class="footer">
                <div class="row">
                    <div class="col-4-sm"> 
                        <button id="okgomb" class="btn w-100" onClick="Modal.close()">OK</button></div>
                </div>
             </div>
         </div>                 
        `
    }

    constructor() {

    }
    create(title, content) {
        this.#modal = this.#getModal(title, content);
      //  document.body.remove(this.#modal);
        return this;
    }
    open() {
      this.#container.innerHTML  = this.#modal;
    }
     static close() {
        document.getElementById("modal").remove();
        document.getElementById("blur").remove();
    }
}