
const deleteButtons = document.querySelectorAll('.delete')

/**
 * Delete function
 */
const clickDelete = function () {
    const modalDelete = document.querySelector('#modalDelete')
    const modal = new bootstrap.Modal(modalDelete)
    modal.show()

    const formDelete = modalDelete.querySelector('form')
    formDelete.action = this.dataset.url

    const token = modalDelete.querySelector('#token')
    token.value = this.dataset.token
}


deleteButtons.forEach(deleteButton=>{
    deleteButton.addEventListener('click',clickDelete)
})

