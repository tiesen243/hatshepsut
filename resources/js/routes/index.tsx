import * as React from 'react'

export default function Index() {
  const [status, setStatus] = React.useState<{
    status: string
    datatabase: string
    timestamp: string
    message: string
  }>({
    status: 'Loading...',
    datatabase: 'Loading...',
    timestamp: new Date().toISOString(),
    message: 'Loading...',
  })

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
      <pre className="mb-8 overflow-x-auto rounded-md bg-secondary p-4 font-mono text-secondary-foreground shadow-md">
        {JSON.stringify(status, null, 2)}
      </pre>

      <p className="text-center text-lg text-muted-foreground">
        Edit
        <code className="mx-1 rounded-sm bg-muted px-0.5 font-mono text-sm">
          resources/js/routes/index.ts
        </code>
        and save to test HMR
      </p>
    </main>
  )
}
