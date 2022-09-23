const successModal = new bootstrap.Modal('#successModal')

document.getElementById('successModal').addEventListener('hidden.bs.modal', event => {
    location.reload()
})