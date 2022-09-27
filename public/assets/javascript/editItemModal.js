const editItemModal = new bootstrap.Modal('#editItemModal', {
    backdrop: false
})

// set the item to edit
var editItem = {}
var setEditItem = (id) => {
    editItem.id = items.find(item => item.id === id).id;
    editItem.name = document.getElementById("editItemNameInput").value,
    editItem.value = document.getElementById("editItemValueInput").value
}

// enable editItemModal buttons on modal dismiss
document.getElementById('editItemModal').addEventListener('hidden.bs.modal', event => {
    document.querySelectorAll("#editItemModal button").forEach(element => {
        element.removeAttribute('disabled')
    })
})

// edit item
document.getElementById("editItemConfirmButton").addEventListener('click', function (event) {
    // disable modal buttons
    document.querySelectorAll("#editItemModal button").forEach(element => {
        element.setAttribute('disabled', true)
    })

    // fetch/then/catch
    let editItemUrl = `api/v1/items/${editItem.id}`;
    fetch(editItemUrl,{
        method: 'PATCH', 
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }, 
        body: JSON.stringify(editItem)
    }).then(function(response) {
        editItemModal.hide()
        if (response.ok) {
            successModal.show()
        } else {
            errorModal.show()
        }        
    }).catch(function(err) {
      // An error has occurred
      editItemModal.hide()
      errorModal.show()
    })
})