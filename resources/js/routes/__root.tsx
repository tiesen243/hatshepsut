import type { QueryClient } from '@tanstack/react-query'
import { Outlet } from 'react-router'
import { QueryClientProvider } from '@tanstack/react-query'

import { ThemeProvider } from '@/hooks/use-theme'
import { createQueryClient } from '@/lib/query-client'

let clientQueryClientSingleton: QueryClient | undefined = undefined
const getQueryClient = () => {
  if (typeof window === 'undefined') return createQueryClient()
  else return (clientQueryClientSingleton ??= createQueryClient())
}

export default function RootLayout() {
  const queryClient = getQueryClient()

  return (
    <ThemeProvider>
      <QueryClientProvider client={queryClient}>
        <Outlet />
      </QueryClientProvider>
    </ThemeProvider>
  )
}
