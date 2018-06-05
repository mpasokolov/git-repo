
window.onload = function () {
  var button = document.getElementById('button');
  button.addEventListener('click', function() {
    loadStatus();
  });
};

function loadStatus () {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://localhost:63342/task4/status.json');
  xhr.send();

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        var button = document.getElementById('button');
        if (response.result === 'success') {
          button.style.backgroundColor = 'green';
        } else if (response.result === 'error') {
          button.style.backgroundColor = 'red';
        }
      }
    }
  }
}

