import { mutationOptions, queryOptions } from '@tanstack/react-query'

export interface IPost {
  id: string
  title: string
  content: string
  createdAt: string
  updatedAt: string
}

export const post = {
  all: {
    queryKey: () => ['posts'],
    queryOptions: () =>
      queryOptions({
        queryKey: [...post.all.queryKey()],
        queryFn: async () => {
          const res = await fetch('/api/posts')
          if (!res.ok) throw new Error('Network response was not ok')
          const json = (await res.json()) as ApiResponse<IPost[]>
          return json.data.map((post) => new Post(post))
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
        Omit<Post, 'id' | 'createdAt' | 'updatedAt'>
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
          updatedPost: Partial<Omit<Post, 'id' | 'createdAt' | 'updatedAt'>>,
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
  private _id: string
  private _title: string
  private _content: string
  private _createdAt: string
  private _updatedAt: string

  constructor(post: IPost) {
    this._id = post.id
    this._title = post.title
    this._content = post.content
    this._createdAt = post.createdAt
    this._updatedAt = post.updatedAt
  }

  get id(): string {
    return this._id
  }
  set id(value: string) {
    this._id = value
  }

  get title(): string {
    return this._title
  }
  set title(value: string) {
    this._title = value
  }

  get content(): string {
    return this._content
  }
  set content(value: string) {
    this._content = value
  }

  get createdAt(): Date {
    return new Date(this._createdAt)
  }
  set createdAt(value: string) {
    this._createdAt = value
  }

  get updatedAt(): Date {
    return new Date(this._updatedAt)
  }
  set updatedAt(value: string) {
    this._updatedAt = value
  }
}
