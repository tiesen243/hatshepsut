const rootElement = document.getElementById('root') as HTMLElement
rootElement.innerHTML = /* HTML */ `
  <div class="text-center">
    <h1 class="mb-8 text-5xl leading-tight font-bold">Vite + TypeScript</h1>
    <div class="p-8">
      <button
        id="counter"
        type="button"
        class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-md bg-primary px-6 text-sm font-medium whitespace-nowrap text-primary-foreground shadow-xs transition-all outline-none hover:bg-primary/90 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 has-[>svg]:px-4 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
      ></button>
    </div>
  </div>
`

const counterButton = document.getElementById('counter') as HTMLButtonElement
let counter = 0
const setCounter = (count: number) => {
  counter = count
  counterButton.innerHTML = `count is ${counter}`
}
counterButton.addEventListener('click', () => setCounter(counter + 1))
setCounter(0)
