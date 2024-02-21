// scripts.js

function enlargeImage(img) {
    // Create an overlay
    let overlay = document.createElement('div');
    overlay.classList.add('overlay');
    document.body.appendChild(overlay);
    overlay.style.display = 'block';

    // Create a container for the enlarged image and close button
    let imageContainer = document.createElement('div');
    imageContainer.classList.add('enlarged-image-container');
    document.body.appendChild(imageContainer);

    // Clone the image to enlarge
    let clone = img.cloneNode();
    clone.classList.add('enlarged-image');
    imageContainer.appendChild(clone);

    // Create a close button
    let closeButton = document.createElement('button');
    closeButton.innerText = 'Close';
    closeButton.classList.add('close-button');
    imageContainer.appendChild(closeButton); // Append close button to the image container

    // Function to remove enlarged image and other elements
    function removeEnlargedElements() {
        document.body.removeChild(imageContainer);
        document.body.removeChild(overlay);
    }

    // Close on button click
    closeButton.addEventListener('click', removeEnlargedElements);

    // Close if overlay is clicked
    overlay.addEventListener('click', removeEnlargedElements);
}