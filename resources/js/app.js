import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

window.addEventListener('update-browser-url', (e) => {
  history.pushState(null, null, e.detail.url)
})
