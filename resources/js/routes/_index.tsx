import { Button } from '@/components/ui/button'
import { useState } from 'react'

export function Component() {
  const [counter, setCounter] = useState(0)
  return (
    <main className="container py-4">
      <h1 className="text-3xl font-bold">Home</h1>
      <p className="my-4 text-lg">
        Welcome to Hatshepsut, a modern React framework.
      </p>
      <Button onClick={() => setCounter(counter + 1)}>
        Count is {counter}
      </Button>
    </main>
  )
}
