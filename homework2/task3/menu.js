
window.onload = function () {
  loadImage();
};

function loadImage () {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:63342/task3/gallary.json');
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        createGallery(response);
      }
    }
  }
}

function createGallery(obj) {
  obj.forEach(function (item) {

    var container = document.createElement('div');
    container.className = 'image-block';
    var text = document.createElement('p');
    text.textContent = item.title;

    var link = document.createElement('a');
    link.href = item.big;

    var img = document.createElement('img');
    img.src = item.small;
    img.style.width = '300px';
    img.style.height = '200px';


    link.appendChild(img);
    container.appendChild(text);
    container.appendChild(link);

    document.body.appendChild(container);
  });
}