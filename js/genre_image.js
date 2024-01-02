document.addEventListener("DOMContentLoaded", function() {
  var imagesContainer = document.querySelector('.images');
  var imagesCount = imagesContainer.childElementCount;

  if (imagesCount <= 4) {
      imagesContainer.style.justifyContent = 'space-between';
  } else {
      imagesContainer.style.justifyContent = 'flex-start';
  }
});