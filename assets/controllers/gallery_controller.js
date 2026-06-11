import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['main', 'thumb', 'counter', 'lightbox', 'lightboxImg', 'lightboxCounter']

    connect() {
        this._idx = 0
        this._imgs = this.thumbTargets.map(t => t.src)
        // If no thumbs (single image), read from main
        if (!this._imgs.length && this.hasMainTarget) {
            this._imgs = [this.mainTarget.src]
        }
        this._total = this._imgs.length
        this._syncMain()

        this._keyHandler = this._onKey.bind(this)
        document.addEventListener('keydown', this._keyHandler)

        // Swipe on main gallery
        this._addSwipe(this.element, () => this._go(1), () => this._go(-1))

        // Swipe inside lightbox
        if (this.hasLightboxTarget) {
            this._addSwipe(this.lightboxTarget, () => this._go(1), () => this._go(-1))
        }
    }

    disconnect() {
        document.removeEventListener('keydown', this._keyHandler)
    }

    select({ params: { index } }) {
        this._idx = index
        this._syncMain()
    }

    next() { this._go(1) }
    prev() { this._go(-1) }

    openLightbox() {
        if (!this.hasLightboxTarget) return
        this._syncLightbox()
        this.lightboxTarget.classList.add('open')
        document.body.style.overflow = 'hidden'
    }

    closeLightbox() {
        if (!this.hasLightboxTarget) return
        this.lightboxTarget.classList.remove('open')
        document.body.style.overflow = ''
    }

    lightboxBackdropClick(e) {
        if (e.target === this.lightboxTarget) this.closeLightbox()
    }

    // ── private ──────────────────────────────────────────────────────

    _go(dir) {
        this._idx = (this._idx + dir + this._total) % this._total
        this._syncMain()
    }

    _syncMain() {
        if (this.hasMainTarget) this.mainTarget.src = this._imgs[this._idx]
        if (this.hasCounterTarget) this.counterTarget.textContent = this._idx + 1
        this.thumbTargets.forEach((t, i) => t.classList.toggle('active', i === this._idx))
        this._syncLightbox()
    }

    _syncLightbox() {
        if (this.hasLightboxImgTarget) this.lightboxImgTarget.src = this._imgs[this._idx]
        if (this.hasLightboxCounterTarget) {
            this.lightboxCounterTarget.textContent = `${this._idx + 1} / ${this._total}`
        }
    }

    _onKey(e) {
        const open = this.hasLightboxTarget && this.lightboxTarget.classList.contains('open')
        if (e.key === 'ArrowRight') this._go(1)
        else if (e.key === 'ArrowLeft') this._go(-1)
        else if (e.key === 'Escape' && open) this.closeLightbox()
    }

    _addSwipe(el, onLeft, onRight) {
        let startX = null
        el.addEventListener('touchstart', e => { startX = e.touches[0].clientX }, { passive: true })
        el.addEventListener('touchend', e => {
            if (startX === null) return
            const dx = e.changedTouches[0].clientX - startX
            if (Math.abs(dx) > 50) dx < 0 ? onLeft() : onRight()
            startX = null
        }, { passive: true })
    }
}
