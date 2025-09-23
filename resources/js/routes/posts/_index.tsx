import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { Trash2Icon } from 'lucide-react'

import type { Post } from '@/api/post'
import { postFilters, postOptions } from '@/api/post'
import { Button } from '@/components/ui/button'
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Link } from 'react-router'

export function Component() {
  return (
    <main className="container py-4">
      <div className="mb-4 flex items-center justify-between gap-4">
        <h1 className="text-2xl font-bold">Posts</h1>
        <Button asChild>
          <Link to="/posts/create">Create Post</Link>
        </Button>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <PostList />
      </div>
    </main>
  )
}

function PostList() {
  const { data, isPending, isError } = useQuery(postOptions.all())

  if (isPending)
    return Array.from({ length: 3 }, (_, i) => <PostCardSkeleton key={i} />)

  if (isError)
    return (
      <p className="text-center text-destructive/80 md:col-span-2 lg:col-span-3">
        Error loading posts.
      </p>
    )

  return data.map((post) => <PostCard key={post.id} post={post} />)
}

function PostCard({ post }: { post: Post }) {
  const queryClient = useQueryClient()
  const { mutate, isPending } = useMutation({
    ...postOptions.delete(post.id),
    onSuccess: async () => {
      await queryClient.invalidateQueries(postFilters.all())
    },
  })

  return (
    <Card className="transition-shadow hover:shadow-lg">
      <CardHeader>
        <CardTitle>{post.title}</CardTitle>
        <CardDescription className="text-sm text-muted-foreground">
          {new Date(post.createdAt).toLocaleDateString()}
        </CardDescription>
        <CardAction>
          <Button
            variant="outline"
            size="icon"
            onClick={() => mutate()}
            disabled={isPending}
          >
            <span className="sr-only">Delete Post</span>
            <Trash2Icon />
          </Button>
        </CardAction>
      </CardHeader>
      <CardContent>
        <p className="line-clamp-2 text-sm text-muted-foreground">
          {post.content.length > 100
            ? `${post.content.slice(0, 100)}...`
            : post.content}
        </p>
      </CardContent>
    </Card>
  )
}

function PostCardSkeleton() {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="w-1/2 animate-pulse rounded bg-current">
          &nbsp;
        </CardTitle>
        <CardDescription className="w-1/4 animate-pulse rounded bg-current">
          &nbsp;
        </CardDescription>
        <CardAction>
          <Button variant="outline" size="icon" disabled>
            <span className="sr-only">Delete Post</span>
            <Trash2Icon />
          </Button>
        </CardAction>
      </CardHeader>
      <CardContent>
        <p className="w-full animate-pulse rounded bg-current text-xs">
          &nbsp;
        </p>
        <p className="mt-1 w-5/6 animate-pulse rounded bg-current text-xs">
          &nbsp;
        </p>
      </CardContent>
    </Card>
  )
}
