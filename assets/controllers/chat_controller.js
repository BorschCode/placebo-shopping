import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['messages', 'input', 'drawer', 'fab']
    static values = { mode: String }  // sidebar | panel | drawer

    connect() {
        this.scrollToBottom()
    }

    scrollToBottom() {
        if (this.hasMessagesTarget) {
            this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight
        }
    }

    // Called after Turbo Stream appends a new message
    messageAdded() {
        this.scrollToBottom()
        if (this.hasInputTarget) this.inputTarget.value = ''
    }

    // Experinza: toggle bottom drawer
    toggleDrawer() {
        if (this.hasDrawerTarget) {
            this.drawerTarget.classList.toggle('open')
            this.scrollToBottom()
        }
    }

    closeDrawer() {
        if (this.hasDrawerTarget) {
            this.drawerTarget.classList.remove('open')
        }
    }
}
