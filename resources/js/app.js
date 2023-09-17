import Cropper from 'cropperjs';
import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
  window.Cropper = Cropper
  window.Sortable = Sortable

  Livewire.on('update-browser-url', (event) => {
    const url = event[0].url
    if (url === undefined) {
      return
    }

    // Make sure we save any Alpine states before pushing to the history
    let state = window.history.state || {}
    if (!state.alpine) {
      state.alpine = {}
    }

    window.history.pushState(state, '', url)
  })

  window.closeModal = () => {
    document.querySelector('body').classList.remove('overflow-hidden')
    document.querySelector('.modal-controller').classList.add('hidden')

    Livewire.dispatch('closeModal')
  }

  window.openModal = (modal, params) => {
    document.querySelector('body').classList.add('overflow-hidden')
    document.querySelector('.modal-controller').classList.remove('hidden')

    Livewire.dispatch('openModal', [modal, params])
  }

  window.addEventListener('close-modal', () => {
    window.closeModal()
  })

  Livewire.on('closeModal', () => {
    document.querySelector('body').classList.remove('overflow-hidden')
    document.querySelector('.modal-controller').classList.add('hidden')
  })
})
