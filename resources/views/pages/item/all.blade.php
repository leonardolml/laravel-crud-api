@extends('layouts.layout')

@section('content')
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
        <span class="fs-4">Items</span>
      </a>
    </header>

    <div class="p-5 mb-4 bg-light rounded-3">
      <div class="container-fluid">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItemModal">
        CREATE AN ITEM
      </button>
        <div class="row py-3">
            <table id="items_table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Deleted at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    <tr>
                      <th scope="row">{{ $item->id }}</th>
                      <td>{{ $item->name }}</td>
                      <td>{{ $item->value }}</td>
                      <td>{{ $item->created_at ?? 'NEVER' }}</td>
                      <td>{{ $item->updated_at ?? 'NEVER' }}</td>
                      <td>{{ $item->deleted_at ?? 'NEVER' }}</td>
                      <td>
                        <button class="btn btn-primary" onclick="window.location.href = 'items/{{ $item->id }}'">DETAILS</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-bs-itemname="{{ $item->name }}" data-bs-itemid="{{ $item->id }}">DELETE</button>
                        <!-- <button class="btn btn-danger" onclick="confirmDelete({{ $item->id }},'{{ $item->name }}')">DELETE</button> -->
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="7" class="text-center">No items found.</td>
                    </tr>
                    @endforelse 
                </tbody>
            </table>
        </div>
      </div>
    </div>
    <footer class="pt-3 mt-4 text-muted border-top">
      © 2022
    </footer>
  </div>

  <!-- confirmDeleteModal -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            Modal body paragraph
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteButton" data-bs-itemid="0">DELETE!</button>
        </div>
      </div>
    </div>
  </div>

  <!-- createItemModal -->
  <div class="modal fade" id="createItemModal" tabindex="-1" aria-labelledby="createItemModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createItemModalLabel">Create an item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="createItemModalBody">
          <input class="form-control mb-2" type="text" name="name" id="createItemNameInput" placeholder="Name">
          <input class="form-control" type="text" name="value" id="createItemValueInput" placeholder="Value">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="createItemCancelButton" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-success" id="createItemConfirmButton">CREATE!</button>
        </div>
      </div>
    </div>
  </div>

  <!-- successModal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalTitle">Success!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="successModalBody">
          <h5 class="text-success text-center">Item created.</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="successModalButton" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- errorModal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalTitle">Error!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="errorModalBody">
          <h5 class="text-danger text-center">Item not created.</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="errorModalButton" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script>
  const cancelButton = document.getElementById('cancelButton')
  const confirmDeleteButton = document.getElementById('confirmDeleteButton')
  confirmDeleteButton.addEventListener('click', event => {
    // Preparing buttons
    confirmDeleteButton.textContent = 'DELETING...'
    cancelButton.disabled = true
    confirmDeleteButton.disabled = true
    // Setting up url
    let item_id = confirmDeleteButton.getAttribute('data-bs-itemid')
    let deleteUrl = "{{ route('v1.items.delete', ':item_id') }}";
    deleteUrl = deleteUrl.replace(':item_id', item_id);
    // Request delete!
    fetch(deleteUrl,{ method: 'DELETE' }).then(function(response) {
        if (response.ok) {
          return response.json();
        }
        console.log(response.text)
        // throw new Error(response.text);
      // return response.json();
    }).then(function(json) {
      // Delete done
      // console.log(json)
      // Adjusting buttons
      confirmDeleteButton.style.visibility = 'hidden'
      cancelButton.textContent = 'OK!'
      cancelButton.disabled = false
      cancelButton.classList.remove("btn-secondary");
      cancelButton.classList.add("btn-success");
    }).catch(function(err) {
      // An error has occurred
      console.log(err.message)
      // alert('Fetch problem: ' + err.message);
    })
  })

  const confirmDeleteModal = document.getElementById('confirmDeleteModal')
  confirmDeleteModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const item_id = button.getAttribute('data-bs-itemid')
    const item_name = button.getAttribute('data-bs-itemname')
    // Update the modal's content.
    const modalTitle = confirmDeleteModal.querySelector('.modal-title')
    const modalBodyParagraph = confirmDeleteModal.querySelector('.modal-body p')
    modalTitle.textContent = `Are you sure?`
    modalBodyParagraph.textContent = `Do you really want to delete ${item_name}?`
    confirmDeleteButton.setAttribute('data-bs-itemid', item_id)
  })
</script>
<script src="assets/javascript/createItemModal.js"></script>
<script src="assets/javascript/successModal.js"></script>
<script src="assets/javascript/errorModal.js"></script>
@endsection