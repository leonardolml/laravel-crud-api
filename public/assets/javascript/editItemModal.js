const editItemModal = new bootstrap.Modal('#editItemModal', {
    backdrop: false
})

// setting up edit modal
var setEditModal = (id) => {
    editItem.id = items.find(item => item.id === id).id
    document.getElementById("editItemNameInput").value = items.find(item => item.id === id).name
    document.getElementById("editItemValueInput").value = items.find(item => item.id === id).value
}


// set the item to edit
var editItem = {}
var setEditItem = () => {
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
    // get data
    setEditItem()
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