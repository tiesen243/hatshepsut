import { data, useLoaderData } from 'react-router'

export async function loader() {
  await new Promise((resolve) => setTimeout(resolve, Math.random() * 100 + 100))

  return data({
    message: 'About Hatshepsut',
    description:
      'This project is dedicated to providing resources and information about Hatshepsut, one of the most successful pharaohs of ancient Egypt.',
  })
}

export function Component() {
  const { message, description } = useLoaderData<typeof loader>()

  return (
    <main className="container py-4">
      <h1 className="mb-2 text-2xl font-bold">{message}</h1>
      <p className="text-lg">{description}</p>
    </main>
  )
}
