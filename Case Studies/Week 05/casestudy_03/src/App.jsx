import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import FormValidationExample from './FormValidationExample'

function App() {
  const [count, setCount] = useState(0)

  return (
    <FormValidationExample/>
  )
}

export default App
