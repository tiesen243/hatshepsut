import * as React from 'react'
import { createRoot } from 'react-dom/client'
import { createBrowserRouter, RouterProvider } from 'react-router'

import RootLayout, { ErrorBoundary, HydrateFallback } from '@/root'
import Index from '@/routes'

const router = createBrowserRouter([
  {
    element: <RootLayout />,
    children: [{ index: true, element: <Index /> }],
    ErrorBoundary,
    HydrateFallback,
  },
])

const rootElement = document.getElementById('root')
createRoot(rootElement as HTMLElement).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>,
)
