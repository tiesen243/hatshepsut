import * as React from 'react'
import { createRoot } from 'react-dom/client'

const rootElement = document.getElementById('root') as HTMLElement

function App() {
  const [count, setCount] = React.useState(0)

  return (
    <div className="flex min-h-[calc(100dvh-3.5rem)] flex-col items-center justify-center gap-6">
      <img
        src="https://vitejs.dev/logo.svg"
        className="mb-2 h-20 animate-bounce"
        alt="Vite logo"
      />
      <h1 className="text-4xl font-bold text-[#646cff] drop-shadow">
        Vite + PHP
      </h1>
      <button
        type="button"
        className="inline-flex h-9 items-center justify-center rounded-md bg-primary px-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
        onClick={() => setCount((count) => count + 1)}
      >
        count is {count}
      </button>

      <p className="text-center text-lg text-muted-foreground">
        Edit
        <code className="mx-1 rounded-sm bg-muted px-0.5 font-mono text-sm">
          resources/js/index.tsx
        </code>
        and save to test HMR
      </p>
    </div>
  )
}

createRoot(rootElement).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>,
)
