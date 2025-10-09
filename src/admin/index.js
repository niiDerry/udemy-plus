import "./main.css";

const ogImgBtn = document.querySelector("#og-img-btn"); // the button for selecting the image
const ogImgCtr = document.querySelector("#og-img-preview"); // the preview container for the image
const ogImgInput = document.querySelector("#up_og_image"); // the input for the image file

const mediaFrame = wp.media({
  title: "Select or Upload Media",
  button: {
    text: "Use this media",
  },
  multiple: false,
});

ogImgBtn.addEventListener("click", (event) => {
  event.preventDefault();
  mediaFrame.open();
});

mediaFrame.on("select", () => {
  const attachment = mediaFrame.state().get("selection").first().toJSON();
  ogImgCtr.src = attachment.sizes.opengraph.url; // update the preview container with the image URL
  ogImgInput.value = attachment.sizes.opengraph.url; // update the input value with the image URL
});
