import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["collection"]

    static values = {
        index: Number,
        prototype: String,
    }

    connect() {
        if (this.indexValue == 0)
            this.add()
    }

    add(event) {
        const item = document.createElement('div')
        item.classList.add('shadow', 'p-3', 'mb-5', 'bg-body', 'rounded')
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue)
        // add button delete
        const deleteButton = document.createElement('button')
        deleteButton.classList.add('btn','btn-warning','m-3')
        deleteButton.textContent = 'Supprimer'
        deleteButton.dataset.action = 'pictures#remove'
        item.appendChild(deleteButton)
        this.collectionTarget.appendChild(item)
        this.indexValue++
    }

    remove(event) {
        event.target.parentNode.remove()
    }
}
