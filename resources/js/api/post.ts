import { mutationOptions, queryOptions } from '@tanstack/react-query'

export interface Post {
  id: string
  title: string
  content: string
  createdAt: string
  updatedAt: string
}

export const postFilters = {
  all: () => ({
    queryKey: ['posts'],
  }),

  byId: (id: string) => ({
    queryKey: ['posts', id],
  }),
}

export const postOptions = {
  all: () =>
    queryOptions({
      ...postFilters.all(),
      queryFn: async () => {
        const response = await fetch('/api/posts')
        if (!response.ok) throw new Error('Network response was not ok')
        return response.json() as Promise<Post[]>
      },
    }),

  byId: (id: string) =>
    queryOptions({
      ...postFilters.byId(id),
      queryFn: async () => {
        const response = await fetch(`/api/posts/${id}`)
        if (!response.ok) throw new Error('Network response was not ok')
        return response.json() as Promise<Post>
      },
    }),

  delete: (id: string) =>
    mutationOptions({
      mutationKey: [...postFilters.byId(id).queryKey, 'delete'],
      mutationFn: async () => {
        const response = await fetch(`/api/posts/${id}`, { method: 'POST' })
        if (!response.ok) throw new Error('Network response was not ok')
        return { success: true }
      },
    }),
}
