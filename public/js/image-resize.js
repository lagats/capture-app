// -------------------
// Image Resize functions
// -------------------

(function(){

  // Create capture object if it doesn't exist
  window.capture = window.capture || {};
  const capture = window.capture || {};

  // Function to compress image
  // src -> https://imagekit.io/blog/how-to-resize-image-in-javascript/
  capture.compressImage = function(imageFile, MAX_WIDTH = 300, MAX_HEIGHT = 300, QUALITY = 0.8) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = function (e) {
          var img = document.createElement("img");
          img.onload = function (event) {
              // Dynamically create a canvas element
              var canvas = document.createElement("canvas");
              
              // var canvas = document.getElementById("canvas");
              var ctx = canvas.getContext("2d");
              
              // Rescale
              var width = img.width;
              var height = img.height;

              // Change the resizing logic
              if (width > height) {
                  if (width > MAX_WIDTH) {
                      height = height * (MAX_WIDTH / width);
                      width = MAX_WIDTH;
                  }
              } else {
                  if (height > MAX_HEIGHT) {
                      width = width * (MAX_HEIGHT / height);
                      height = MAX_HEIGHT;
                  }
              }

              // Actual resizing
              var canvas = document.createElement("canvas");
              canvas.width = width;
              canvas.height = height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0, width, height);
              
              // Show resized image in preview element
              var dataurl = canvas.toDataURL('image/jpeg', QUALITY);
              
              // get filesize
              dataUrlToKb(dataurl);
              
              // return dataurl
              resolve(dataurl);
          }
          img.src = e.target.result;
      }
      reader.readAsDataURL(imageFile);
    });
  }

  // get image data from file
  capture.fileToDataUri = function(field) {
      return new Promise((resolve) => {
          const reader = new FileReader();
          reader.addEventListener("load", () => {
              resolve(reader.result);
          });
          reader.readAsDataURL(field);
      });
  };

  // get size from dataurl
  // src -> https://stackoverflow.com/a/47852494
  function dataUrlToKb(src) {
    let base64Length = src.length - (src.indexOf(',') + 1);
    let padding = (src.charAt(src.length - 2) === '=') ? 2 : ((src.charAt(src.length - 1) === '=') ? 1 : 0);
    let fileSize = base64Length * 0.75 - padding;
    console.log("Image FileSize: " + fileSize / 1024 + " KB");
  }

})();