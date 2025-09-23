import { createBrowserRouter, Outlet, RouterProvider } from 'react-router'
import { createRoot } from 'react-dom/client'

import { ErrorBoundary } from '@/components/error-boundary'
import { Header } from '@/components/header'
import { HydrateFallback } from '@/components/hydrate-fallback'
import { Providers } from '@/components/providers'

const router = createBrowserRouter([
  {
    element: (
      <Providers>
        <Header />
        <Outlet />
      </Providers>
    ),
    children: [
      { index: true, lazy: () => import('@/routes/_index') },
      { path: '/about', lazy: () => import('@/routes/about') },
      { path: '/posts', lazy: () => import('@/routes/posts/_index') },
      { path: '/posts/create', lazy: () => import('@/routes/posts/create') },
    ],
    hydrateFallbackElement: <HydrateFallback />,
    errorElement: <ErrorBoundary />,
  },
])

const rootElement = document.getElementById('root') as HTMLElement
createRoot(rootElement).render(<RouterProvider router={router} />)
