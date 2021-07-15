import { fetchData } from "./api.js"

const messageButtons = `
<a href="{{ path('game_message_edit',{'id':idGame,'idMessage':convMessage.id}) }}">
							<button class="btn btn-danger">Editer</button>
						</a>
						<form method="post" class="d-inline" action="{{ path('game_message_delete', {'id':idGame,'idMessage':convMessage.id}) }}" onsubmit="return confirm('Etes vous sur de vouloir supprimer ce message ?');">
							<input type="hidden" name="_token" value="{{ csrf_token('delete' ~ convMessage.id) }}">
							<button class="btn btn-danger">Supprimer</button>
						</form>`

const currentUser = document.querySelector('#user')
const messageCustomize = user => {
    return user.username === fetchData ? messageButtons : ''
}

const updated = convMessage => {
    return convMessage.updatedAt ? convMessage.updatedAt : ""
}

const message =(convMessage, user) => `
<div class="card mt-1">
				<div class="card-header">
					<img src="${user.avatar}" class="rounded-circle img-thumbnail" width="75" alt="Avatar de ${user.username}">
					${user.username}
                    ${messageCustomize(user)}
				</div>
				<div class="card-body">
					<p>${convMessage.body}</p>
					<footer class="blockquote-footer">Posté le :
						${convMessage.createdAt}
						${updated(convMessage)}</footer>
				</div>
			</div>`

export const oneMessage = (convMessage, divId) => {
    const messageDiv = document.querySelector(`#${divId}`)
    messageDiv.innerHTML += message(convMessage.user, convMessage)
}

export const AllMessage = (convMessages, divId) => {

    convMessages.forEach(convMessage => {
        const messagesDiv = document.querySelector(`#${divId}`)
        messagesDiv.innerhtml += message(convMessage.user, convMessage)
    });
}
