export async function loader() {
  return { message: 'Hello from loader' }
}

export function Component() {
  return (
    <div>
      <h1>Welcome to the Index Page</h1>
    </div>
  )
}
