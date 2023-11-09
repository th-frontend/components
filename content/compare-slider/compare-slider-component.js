const template = document.createElement("template");

const thumbStyles = `
  background-color: var(--thumb-background-color);
  background-image: var(--thumb-background-image);
  background-size: 90%;
  background-position: center center;
  background-repeat: no-repeat;
  border-radius: var(--thumb-radius);
  border: var(--thumb-border-size) var(--thumb-border-color) solid;
  color: var(--thumb-border-color);
  width: var(--thumb-size);
  height: var(--thumb-size);
`;

const thumbFocusStyles = `
  box-shadow: 0px 0px 0px var(--focus-width) var(--focus-color);
`;

const thumbSvgWidth = 4;

template.innerHTML = /*html*/`
  <style>
    :host {
      --exposure: 50%;
      --thumb-background-color: hsla(0, 0%, 100%, 0.9);
      --thumb-background-image: url('data:image/svg+xml;utf8,<svg viewbox="0 0 60 60"  width="60" height="60" xmlns="http://www.w3.org/2000/svg"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="${thumbSvgWidth}" d="M20 20 L10 30 L20 40"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="${thumbSvgWidth}" d="M40 20 L50 30 L40 40"/></svg>');
      --thumb-size: clamp(3em, 10vmin, 5em);
      --thumb-radius: 50%;
      --thumb-border-color: hsla(0, 0%, 0%, 0.9);
      --thumb-border-size: 2px;
      --focus-width: var(--thumb-border-size);
      --focus-color: hsl(200, 100%, 80%);
      --divider-width: 2px;
      --divider-color: hsla(0, 0%, 0%, 0.9);
      display: flex;
      flex-direction: column;
      margin: 0 auto;
      overflow: hidden;
      position: relative;
      max-width: 400px;
    }
    div[slot='image-2'] img {
      width: 100%;
      object-fit: cover;
    }
    ::slotted([slot='image-2']) {
      clip-path: polygon(
        calc(var(--exposure) + var(--divider-width)/2) 0, 
        100% 0, 
        100% 100%, 
        calc(var(--exposure) + var(--divider-width)/2) 100%);
    }
    slot {
      display: flex;
      flex-direction: column;
      width: 100%;
    }
    slot[name='image-2'] {
      position: absolute;
      top:0;
      filter: drop-shadow(calc(var(--divider-width) * -1) 0 0 var(--divider-color));
    }
    .visually-hidden {
      border: 0; 
      clip: rect(0 0 0 0); 
      clip-path: polygon(0px 0px, 0px 0px, 0px 0px);
      -webkit-clip-path: polygon(0px 0px, 0px 0px, 0px 0px);
      height: 1px; 
      margin: -1px;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
      white-space: nowrap;
    }
    .titles {
      height: 100%;
      width: 100%;
      position: absolute;
      display: grid;
      grid-template-columns: repeat(2,minmax(0,1fr));
      align-items: end;
      opacity: 1;
      transition: opacity .5s;
      z-index: 0;
      text-align: center;
    }
    .titles span {
        display: block;
        background: rgba(0, 0, 0, 0.5);
        line-height: 38px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        color: #fff;
        width: 40%;
        font-family: Arial, sans-serif;
    }
    .title-before{
      justify-self: start;
    }
    .title-after {
      justify-self: end;
    }
    .title-hidden {
      opacity: 0;
    }
    :host(:hover) .titles {
        opacity: 1;
    }
    label {
      align-items: stretch;
      display: flex;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }
    input {
      cursor: col-resize;
      margin: 0 calc(var(--thumb-size) / -2);
      width: calc(100% + var(--thumb-size));
      appearance: none;
      -webkit-appearance: none;
      background: none;
      border: none;
      position: relative;
      z-index: 1;
    }
    ::-moz-range-thumb {
      ${thumbStyles}
    }
    ::-webkit-slider-thumb {
      -webkit-appearance: none;
      ${thumbStyles}
    }
    input:focus::-moz-range-thumb {
      ${thumbFocusStyles}
    }
    input:focus::-webkit-slider-thumb {
      ${thumbFocusStyles}
    }
  </style>
  <slot name="image-1"></slot>
  <slot name="image-2"></slot>
  
  <label>
    <span class="visually-hidden js-label-text">
      Control how much of each overlapping image is shown. 
      0 means the first image is completely hidden and the second image is fully visible.
      100 means the first image is fully visible and the second image is completely hidden.
      50 means both images are half-shown half-hidden.
    </span>
    <input type="range" value="50" min="0" max="100"/>
    <div class="titles">
      <span class="title-before">Before</span>
      <span class="title-after">After</span>
    </div>
  </label>
`;

class ImageCompare extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: "open" });
  }

  connectedCallback() {
    this.shadowRoot.appendChild(template.content.cloneNode(true));
    
    ['input', 'change'].forEach((eventName) => {
      this.shadowRoot.querySelector("input").addEventListener(
        eventName,
        ({ target }) => {
          if (this.animationFrame) cancelAnimationFrame(this.animationFrame);

          let prevExposure = 50

          this.animationFrame = requestAnimationFrame(() => {
            this.shadowRoot.host.style.setProperty('--exposure', `${target.value}%`)

            if (target.value <= 20 && prevExposure > target.value ) {
              this.shadowRoot.querySelector('.title-before').classList.add('title-hidden')
            } else {
              this.shadowRoot.querySelector('.title-before').classList.remove('title-hidden')
            }

            if (target.value >= 80 && prevExposure < target.value ) {
              this.shadowRoot.querySelector('.title-after').classList.add('title-hidden')
            } else {
              this.shadowRoot.querySelector('.title-after').classList.remove('title-hidden')
            }

            prevExposure = target.value


          });
        },
      );
    });

    const customLabel = this.shadowRoot.host.getAttribute('label-text');
    if(customLabel) {
      this.shadowRoot.querySelector(".js-label-text").textContent = customLabel;
    }
  }

}

customElements.define("image-compare", ImageCompare);
