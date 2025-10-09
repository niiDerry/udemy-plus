import Rating from "@mui/material/Rating"; // Ensure MUI Rating is imported
import { render, useState, useEffect } from "@wordpress/element"; // Import render from @wordpress/element
import apiFetch from "@wordpress/api-fetch"; // Import apiFetch for making REST API calls

// Component to handle recipe rating
function RecipeRating(props) {
  const [avgRating, setAvgRating] = useState(props.avgRating); // State to hold average rating
  const [permission, setPermission] = useState(props.loggedIn); // State to manage if user can rate

  // Disable rating if user has already rated
  useEffect(() => {
    if (props.ratingCount) {
      setPermission(false);
    }
  }, []);

  return (
    <Rating
      value={avgRating} // Controlled component with avgRating state
      precision={0.5} // Allow half-star ratings
      onChange={async (event, rating) => {
        // Handle rating change
        if (!permission) {
          return alert(
            "You have already rated this recipe or you need to log in"
          );
        }

        setPermission(false); // Prevent further ratings in this session

        const response = await apiFetch({
          path: "up/v1/rate", // Ensure this matches your registered REST route
          method: "POST", // Use POST method for submitting data
          data: {
            postID: props.postID, // Pass the post ID
            rating, // Pass the selected rating
          },
        });

        // Handle the response
        if (response.status === 2) {
          setAvgRating(response.rating); // Update average rating state
        }
      }}
    />
  );
}

// Frontend script for recipe summary block
document.addEventListener("DOMContentLoaded", () => {
  const block = document.querySelector("#recipe-rating"); // Ensure this ID matches the one in PHP
  if (!block) {
    console.warn("No #recipe-rating element found on this page.");
    return;
  }
  const postID = parseInt(block.dataset.postId); // Convert to integer
  const avgRating = parseFloat(block.dataset.avgRating); // Convert to float
  const loggedIn = !!block.dataset.loggedIn; // Convert to boolean
  const ratingCount = !!parseInt(block.dataset.ratingCount);

  render(
    <RecipeRating
      postID={postID}
      avgRating={avgRating}
      loggedIn={loggedIn}
      ratingCount={ratingCount}
    />, // Render the RecipeRating component
    block // Target the block element
  );
});
