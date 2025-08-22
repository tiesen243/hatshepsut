import * as React from 'react'
import { createRoot } from 'react-dom/client'

const rootElement = document.getElementById('root')

function Page() {
  const [counter, setCounter] = React.useState(0)

  return (
    <main>
      <h1 className="text-3xl font-bold underline">Hello, Vite + React!</h1>
      <p className="mt-4">
        <button
          className="rounded bg-blue-500 px-4 py-2 text-white"
          onClick={() => setCounter(counter + 1)}
        >
          Counter: {counter}
        </button>
      </p>
      <p className="mt-4">
        Edit <code>resources/views/pages/index.tsx</code> to see changes.
      </p>
    </main>
  )
}

createRoot(rootElement!).render(<Page />)
