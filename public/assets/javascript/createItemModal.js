const createItemModal = new bootstrap.Modal('#createItemModal', {
    backdrop: false
})

// enable createItemModal buttons on modal dismiss
document.getElementById('createItemModal').addEventListener('hidden.bs.modal', event => {
    document.querySelectorAll("#createItemModal button").forEach(element => {
        element.removeAttribute('disabled')
    })
})

// create item
document.getElementById("createItemConfirmButton").addEventListener('click', function (event) {
    // disable modal buttons
    document.querySelectorAll("#createItemModal button").forEach(element => {
        element.setAttribute('disabled', true)
    })
    // get data
    let data = {
        name: document.getElementById("createItemNameInput").value,
        value: document.getElementById("createItemValueInput").value
    }
    // fetch/then/catch
    let createItemUrl = "api/v1/items";
    fetch(createItemUrl,{
        method: 'POST', 
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }, 
        body: JSON.stringify(data)
    }).then(function(response) {
        createItemModal.hide()
        if (response.ok) {
            successModal.show()
        } else {
            errorModal.show()
        }        
    }).catch(function(err) {
      // An error has occurred
      createItemModal.hide()
      errorModal.show()
    })
})