import { mutationOptions, queryOptions } from '@tanstack/react-query'

export interface IPost {
  id: string
  title: string
  content: string
  created_at: string
  updated_at: string
}

interface AllQuery {
  page?: number
  limit?: number
}

export const post = {
  all: {
    queryKey: (query: AllQuery = {}) => ['posts', query],
    queryOptions: (query: AllQuery) =>
      queryOptions({
        queryKey: [...post.all.queryKey(query)],
        queryFn: async () => {
          const searchParams = new URLSearchParams()
          if (query.page) searchParams.append('page', query.page.toString())
          if (query.limit) searchParams.append('limit', query.limit.toString())

          const res = await fetch(`/api/posts?${searchParams.toString()}`)
          if (!res.ok) throw new Error('Network response was not ok')
          const json = (await res.json()) as ApiResponse<{
            posts: IPost[]
            total_pages: number
          }>

          return {
            posts: json.data.posts.map((post) => new Post(post)),
            totalPages: json.data.total_pages,
          }
        },
      }),
  },

  byId: {
    queryKey: (id: string) => [...post.all.queryKey(), id],
    queryOptions: (id: string) =>
      queryOptions({
        queryKey: [...post.byId.queryKey(id)],
        queryFn: async () => {
          const res = await fetch(`/api/posts/${id}`)
          if (!res.ok) throw new Error('Network response was not ok')
          const json = (await res.json()) as ApiResponse<IPost>
          return new Post(json.data)
        },
      }),
  },

  create: {
    mutationKey: () => [...post.all.queryKey(), 'create'],
    mutationOptions: () =>
      mutationOptions<
        ApiResponse<unknown, { title?: string; content?: string }>,
        { title?: string; content?: string } | Error,
        Omit<IPost, 'id' | 'createdAt' | 'updatedAt'>
      >({
        mutationKey: [...post.create.mutationKey()],
        mutationFn: async (newPost) => {
          const res = await fetch('/api/posts/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newPost),
          })
          const json = (await res.json()) as ApiResponse<
            unknown,
            { title?: string; content?: string }
          >

          if (!res.ok) {
            if (res.status === 400) throw json.error
            else throw new Error(json.message)
          } else return json
        },
      }),
  },

  update: {
    mutationKey: (id: string) => [...post.all.queryKey(), 'update', id],
    mutationOptions: (id: string) =>
      mutationOptions({
        mutationKey: [...post.update.mutationKey(id)],
        mutationFn: async (
          updatedPost: Partial<Omit<IPost, 'id' | 'createdAt' | 'updatedAt'>>,
        ) => {
          const res = await fetch(`/api/posts/${id}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedPost),
          })
          const json = (await res.json()) as ApiResponse<
            unknown,
            { title?: string; content?: string }
          >

          if (!res.ok) {
            if (res.status === 400) throw json.error
            else throw new Error(json.message)
          } else return json
        },
      }),
  },

  delete: {
    mutationKey: (id: string) => [...post.all.queryKey(), 'delete', id],
    mutationOptions: (id: string) =>
      mutationOptions({
        mutationKey: [...post.delete.mutationKey(id)],
        mutationFn: async () => {
          const res = await fetch(`/api/posts/${id}/delete`, {
            method: 'POST',
          })
          const json = (await res.json()) as ApiResponse

          if (!res.ok) throw new Error(json.message)
          return json
        },
      }),
  },
} as const

export class Post {
  id: string
  title: string
  content: string
  createdAt: Date
  updatedAt: Date

  constructor(post: IPost) {
    this.id = post.id
    this.title = post.title
    this.content = post.content
    this.createdAt = new Date(post.created_at)
    this.updatedAt = new Date(post.updated_at)
  }
}
