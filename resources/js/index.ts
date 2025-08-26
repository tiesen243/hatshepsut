const rootElement = document.getElementById('root') as HTMLElement
rootElement.innerHTML = /* HTML */ `<div>
  <pre
    id="status"
    class="mb-8 overflow-x-auto rounded-md bg-secondary p-4 text-secondary-foreground shadow-md"
  >
    loading...
  </pre>

  <p class="text-center text-lg text-muted-foreground">
    Edit
    <code class="mx-1 rounded-sm bg-muted px-0.5 font-mono text-sm"
      >resources/js/index.ts</code
    >
    and save to test HMR
  </p>
</div>`

window.addEventListener('DOMContentLoaded', () => {
  const statusElement = document.getElementById('status') as HTMLElement

  fetch('/api/health').then(async (response) => {
    const data = await response.json()
    statusElement.innerText = JSON.stringify(data, null, 2)
  })
})
