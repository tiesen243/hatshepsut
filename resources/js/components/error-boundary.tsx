import { isRouteErrorResponse, useRouteError } from 'react-router'

export function ErrorBoundary() {
  const error = useRouteError()

  if (isRouteErrorResponse(error))
    return (
      <main className="flex min-h-screen items-center justify-center bg-background text-foreground">
        <h1 className="mr-5 inline-block border-r py-3 pr-6 text-2xl font-medium">
          {error.status}
        </h1>
        <h2 className="m-0 text-sm">
          {error.status === 404
            ? 'This page could not be found.'
            : error.statusText}
        </h2>
      </main>
    )

  return (
    <main className="flex min-h-screen flex-col items-center justify-center bg-background text-foreground">
      <div className="flex items-center">
        <h1 className="mr-5 inline-block border-r py-3 pr-6 text-2xl font-medium">
          Opps
        </h1>
        <h2 className="m-0 text-sm">
          {(error as Error).message ?? 'Unknown error'}
        </h2>
      </div>

      {import.meta.env.DEV && (
        <pre className="container mt-4 max-w-4xl overflow-x-auto rounded-lg bg-secondary p-4 text-secondary-foreground">
          {(error as Error).stack}
        </pre>
      )}
    </main>
  )
}
