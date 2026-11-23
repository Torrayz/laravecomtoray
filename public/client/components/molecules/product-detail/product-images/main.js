document.addEventListener('DOMContentLoaded', function () {
  const gallery = document.querySelector('[data-product-gallery]');
  if (!gallery) return;

  const mainImg = gallery.querySelector('[data-gallery-main]');
  const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
  const prevBtn = gallery.querySelector('[data-gallery-prev]');
  const nextBtn = gallery.querySelector('[data-gallery-next]');
  if (!mainImg || thumbs.length === 0) return;

  let currentIndex = 0;

  function setActive(index) {
    currentIndex = (index + thumbs.length) % thumbs.length;
    const src = thumbs[currentIndex].getAttribute('data-src');
    if (src) {
      mainImg.src = src;
      mainImg.classList.remove('is-zoomed');
    }
    thumbs.forEach(function (thumb, i) {
      thumb.classList.toggle('active', i === currentIndex);
    });
  }

  thumbs.forEach(function (thumb, idx) {
    thumb.addEventListener('click', function () {
      setActive(idx);
    });
  });

  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      setActive(currentIndex - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      setActive(currentIndex + 1);
    });
  }

  mainImg.addEventListener('click', function () {
    mainImg.classList.toggle('is-zoomed');
  });

  setActive(0);
});
