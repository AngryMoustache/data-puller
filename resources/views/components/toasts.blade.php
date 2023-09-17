<div wire:ignore>
    <div id="toasts"></div>
</div>

<script>
    window.addEventListener('toast', (event) => {
        let $toast = document.createElement('div')
        let $toastMessage = document.createElement('p')

        $toastMessage.appendChild(document.createTextNode(event.detail[0].message))
        $toast.classList.add('toasts-toast', 'border', 'border-background', 'bg-' + event.detail[0].color)
        $toast.appendChild($toastMessage)
        document.getElementById('toasts').appendChild($toast)

        window.setTimeout(() => { $toast.classList.add('toasts-toast-leave') }, 5000)
        window.setTimeout(() => { $toast.remove() }, 6000)
    })
</script>
