import Alpine from 'alpinejs'
import Cropper from 'cropperjs';
import Sortable from 'sortablejs';
import 'livewire-sortable'

window.Cropper = Cropper
window.Sortable = Sortable
window.Alpine = Alpine

Alpine.start()

window.addEventListener('update-browser-url', (e) => {
  history.replaceState(null, null, e.detail.url)
})

window.closeModal = () => {
  document.querySelector('body').classList.remove('overflow-hidden')
  document.querySelector('.modal-controller').classList.add('hidden')

  window.Livewire.emit('closeModal')
}

window.openModal = (modal, params) => {
  document.querySelector('body').classList.add('overflow-hidden')
  document.querySelector('.modal-controller').classList.remove('hidden')

  window.Livewire.emit('openModal', modal, params)
}

window.addEventListener('close-modal', () => {
  window.closeModal()
})
