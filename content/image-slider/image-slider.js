class ImageSlider extends HTMLElement {
  constructor() {
    super();
    this.shadow = this.attachShadow({ mode: 'open' });
    this.images = JSON.parse(this.getAttribute('images')) || [];
    this.currentImageIndex = 0;
    this.autoplay = this.hasAttribute('autoplay');
    this.autoplayInterval = parseInt(this.getAttribute('autoplay-interval')) || 3000;
    this.autoplayTimer = null;
  }

  connectedCallback() {
    this.render();
    this.addEventListeners();
    if (this.autoplay) {
      this.startAutoplay();
    }
  }

  disconnectedCallback() {
    this.stopAutoplay();
  }

  render() {
    const style = `
      .slider-container {
        width: var(--slider-width, 100%);
        margin: auto;
        overflow: hidden;
        position: relative;
      }
      .slider {
        position: relative;
        width: 100%;
        display: flex;
      }
      .slide {
        width: 100%;
        transition: transform 0.5s ease;
        flex-shrink: 0;
      }
      .slide img {
        width: 100%;
        object-fit: contain;
      }
      .arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        font-size: 24px;
        padding: 10px;
        z-index: 2;
      }
      .arrow.left {
        left: 10px;
      }
      .arrow.right {
        right: 10px;
      }
      .dots {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
      }
      .dot {
        cursor: pointer;
        height: 10px;
        width: 10px;
        margin: 0 5px;
        background-color: grey;
        border-radius: 50%;
        display: inline-block;
      }
      .dot.active {
        background-color: black;
      }
    `;

    const sliderHTML = `
      <div class="slider-container">
        <div class="slider">
          ${this.images.map((img, index) => `
            <div class="slide" style="transform:translateX(-${this.currentImageIndex * 100}%)">
              <img src="${img}">
            </div>
          `).join('')}
        </div>
        <button class="arrow left">&#10094;</button>
        <button class="arrow right">&#10095;</button>
        <div class="dots">
          ${this.images.map((_, index) => `
            <span class="dot ${index === this.currentImageIndex ? 'active' : ''}" data-index="${index}"></span>
          `).join('')}
        </div>
      </div>
    `;

    this.shadow.innerHTML = `<style>${style}</style>${sliderHTML}`;
  }

  addEventListeners() {
    const dots = this.shadow.querySelectorAll('.dot');
    dots.forEach(dot => {
      dot.addEventListener('click', (e) => {
        this.currentImageIndex = parseInt(e.target.dataset.index);
        this.updateSlide();
      });
    });

    const leftArrow = this.shadow.querySelector('.arrow.left');
    const rightArrow = this.shadow.querySelector('.arrow.right');

    leftArrow.addEventListener('click', () => this.changeSlide(-1));
    rightArrow.addEventListener('click', () => this.changeSlide(1));
  }

  changeSlide(direction) {
    this.currentImageIndex = (this.currentImageIndex + direction + this.images.length) % this.images.length;
    this.updateSlide();
    if (this.autoplay) {
      this.startAutoplay(); // Restart autoplay to reset the timer
    }
  }

  updateSlide() {
    const slides = this.shadow.querySelectorAll('.slide');
    slides.forEach(slide => {
      slide.style.transform = `translateX(-${this.currentImageIndex * 100}%)`;
    });

    const dots = this.shadow.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === this.currentImageIndex);
    });
  }

  startAutoplay() {
    this.stopAutoplay(); // Stop any existing timer
    this.autoplayTimer = setInterval(() => {
      this.changeSlide(1);
    }, this.autoplayInterval);
  }

  stopAutoplay() {
    if (this.autoplayTimer) {
      clearInterval(this.autoplayTimer);
      this.autoplayTimer = null;
    }
  }
}

customElements.define('image-slider', ImageSlider);
