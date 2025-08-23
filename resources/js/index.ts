const rootElement = document.getElementById('root') as HTMLElement
rootElement.innerHTML = /* HTML */ `<div
  class="flex min-h-[calc(100dvh-3.5rem)] flex-col items-center justify-center gap-6"
>
  <img
    src="https://vitejs.dev/logo.svg"
    class="mb-2 h-20 animate-bounce"
    alt="Vite logo"
  />
  <h1 class="text-4xl font-bold text-[#646cff] drop-shadow">Vite + PHP</h1>
  <button
    id="counter"
    class="inline-flex h-9 items-center justify-center rounded-md bg-primary px-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
    type="button"
  >
    count is 0
  </button>

  <p class="text-center text-lg text-muted-foreground">
    Edit
    <code class="mx-1 rounded-sm bg-muted px-0.5 font-mono text-sm"
      >resources/js/index.ts</code
    >
    and save to test HMR
  </p>
</div>`

const counterButton = document.getElementById('counter') as HTMLButtonElement
let counter = 0
const setCounter = (count: number) => {
  counter = count
  counterButton.innerHTML = `count is ${counter}`
}
counterButton.addEventListener('click', () => setCounter(counter + 1))
setCounter(0)
