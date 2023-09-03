function addInputArgomento(container) {
    const oninput = "appendNewIfLast(this, this.parentElement)";
    const onblur = "removeIfNotLast(this, this.parentElement)";

    inputHTML = "<input type='text' name='argomenti[]' list='argomenti' class='form-control mb-1' placeholder='Nuovo argomento' onblur='" + onblur + "' oninput='" + oninput + "'>";
    container.insertAdjacentHTML('beforeend', inputHTML);
}

function removeIfNotLast(input, container) {
    if (input.value.trim() === "") {
        if (container.lastElementChild !== input) {
            container.removeChild(input);
        }
    }
}

function appendNewIfLast(input, container) {
    if (input.value.trim() !== "") {
        if (container.lastElementChild === input) {
            addInputArgomento(container);
        }
    }
}