const counterValue = document.getElementById('counter-value')
const counterButton = document.getElementById('counter-button')

let count = 0

const setCount = (value: number) => {
  count = value
  if (counterValue) counterValue.innerHTML = `You clicked ${count} times`
}

if (counterButton)
  counterButton.addEventListener('click', () => {
    setCount(count + 1)
  })

setCount(0)
