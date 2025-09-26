import * as React from 'react'
import { createBrowserRouter, RouterProvider } from 'react-router'
import { createRoot } from 'react-dom/client'

import { ErrorBoundary } from '@/components/error-boundary'
import { HydrateFallback } from '@/components/hydrate-fallback'
import RootLayout from '@/routes/__layout'

const rootElement = document.getElementById('root') as HTMLElement

const router = createBrowserRouter([
  {
    element: <RootLayout />,
    children: [
      { index: true, lazy: () => import('./routes/_index') },
      {
        path: '/posts',
        lazy: () => import('./routes/posts/__layout'),
        children: [
          { index: true, lazy: () => import('./routes/posts/_index') },
          { path: 'create', lazy: () => import('./routes/posts/create') },
          { path: ':postId', lazy: () => import('./routes/posts/[id]') },
        ],
      },
    ],
    HydrateFallback,
    ErrorBoundary,
  },
])

createRoot(rootElement).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>,
)
