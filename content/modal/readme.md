# Th Modal for Images and YT Video

How to use
```
<!-- TH Modal Popup - This goes in borders -->
<script src="https://cdn.treehouseinternetgroup.com/cms_core/assets/js/th_modals.js" type="text/javascript"></script>
<script>
    const imagePopups = new ThModal({mode:'image', groupSelector: '[data-modal-gallery]', itemSelector: '[data-modal-image]'});
    const videoPopups = new ThModal({mode:'video', groupSelector: '[data-modal-videos]', itemSelector: '[data-modal-video]',useVideoElement: true});
</script>

<!-- Image Popup - this is the code to trigger the modal-->
<div data-modal-gallery>
  <a data-modal-image href="https://cdn.treehouseinternetgroup.com/core/images/radon/radon-mitigation/indoor-air/radon-dangers-lg.jpg">
    Pop an Image Modal
    <!-- Add optional image to click -->
  </a>
</div>

<!-- Video File Popup - this is the code to trigger the modal -->
<div class="cn-video-item" data-modal-videos="">
    <a href="https://www.youtube.com/embed/iJJU6aXYZWE?si=_k-A7T2veQFYV5zp&amp;autoplay=1" title="Klaus Larsen - Owner, Klaus Roofing Systems" data-modal-video="" contenteditable="false" style="cursor: pointer;">
      Pop a YouTube embed in a Modal
      <!-- Add optional image to click -->
    </a>
</div>
```
