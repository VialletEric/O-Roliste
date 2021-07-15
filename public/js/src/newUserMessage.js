import { sendingApiConfing, sendData, fetchData } from "./api.js"
import { oneMessage } from "./renderMessage.js"

const dataFromForm = () => {
    const bodyMessage = document.querySelector('#textBody').value

    return {
        "body": bodyMessage,

    }
}

/**
 * create config for the HTTP request
 * @returns {object}
 */
const formToApiConfig = () => sendingApiConfing(dataFromForm(), "POST")

const conversationId = location.pathname.split('/')[2]
const apiRoute = `/api/v1/message/user/conversation/${conversationId}`

const apiCallback = (response) => {
    console.log(response)
}

const displayNewMessage = convMessage => {
    oneMessage(convMessage, 'userMessages')
}

const afterResponse = (response) => {
    console.log(response)
    location.reload()
}

/**
 * handle message form submit event
 * @param {event} evt 
 */
export const addMessage = evt => {
    //evt.preventDefault()
    console.log(formToApiConfig())
    sendData(apiRoute, formToApiConfig(), afterResponse)

}

