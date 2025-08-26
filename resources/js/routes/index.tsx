import * as React from 'react'

export default function Index() {
  const [status, setStatus] = React.useState<{
    status: string
    datatabase: string
    timestamp: string
    message: string
  }>()

  React.useEffect(() => {
    const abortController = new AbortController()

    fetch('/api/health', { signal: abortController.signal })
      .then((response) => response.json())
      .then((data) => setStatus(data))
      .catch((error) => {
        if (error.name !== 'AbortError')
          console.error('Failed to fetch health status:', error)
      })

    return () => {
      abortController.abort()
    }
  }, [])

  return (
    <main className="container py-4">
      <pre className="overflow-x-auto rounded-md bg-secondary p-4 font-mono text-secondary-foreground shadow-md">
        {status ? JSON.stringify(status, null, 2) : 'Loading...'}
      </pre>
    </main>
  )
}
