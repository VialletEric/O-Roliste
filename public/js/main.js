import {addMessage} from './src/newUserMessage.js'

const addMessageForm = document.querySelector('#addMessage')
addMessageForm.addEventListener('submit', addMessage)

