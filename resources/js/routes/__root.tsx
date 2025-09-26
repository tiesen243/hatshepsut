import { Outlet } from 'react-router'

import { ThemeProvider } from '@/hooks/use-theme'

export default function RootLayout() {
  return (
    <ThemeProvider>
      <Outlet />
    </ThemeProvider>
  )
}
