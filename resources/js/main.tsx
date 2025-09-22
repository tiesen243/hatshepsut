import { createRoot } from 'react-dom/client'
import { createBrowserRouter, Outlet, RouterProvider } from 'react-router'

import { ThemeProvider } from '@/hooks/use-theme'
import { Header } from '@/components/header'
import { HydrateFallback } from '@/components/hydrate-fallback'
import { ErrorBoundary } from '@/components/error-boundary'

const router = createBrowserRouter([
  {
    element: (
      <ThemeProvider>
        <Header />
        <Outlet />
      </ThemeProvider>
    ),
    children: [
      { index: true, lazy: () => import('@/routes/_index') },
      { path: '/about', lazy: () => import('@/routes/about') },
    ],
    hydrateFallbackElement: <HydrateFallback />,
    errorElement: <ErrorBoundary />,
  },
])

const rootElement = document.getElementById('root') as HTMLElement
createRoot(rootElement).render(<RouterProvider router={router} />)
