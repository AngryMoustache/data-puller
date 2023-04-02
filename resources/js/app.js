import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

window.addEventListener('update-browser-url', (e) => {
  history.pushState(null, null, e.detail.url)
})

window.closeModal = () => {
  document.querySelector('.modal-controller').classList.add('hidden')
  window.Livewire.emit('closeModal')
}

window.openModal = (modal, params) => {
  document.querySelector('.modal-controller').classList.remove('hidden')
  window.Livewire.emit('openModal', modal, params)
}
