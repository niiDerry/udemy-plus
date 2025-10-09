import { registerBlockType } from "@wordpress/blocks"; //for registering the block
import icons from "../../icons.js";
import "./main.css"; //block styles

import edit from "./edit.js"; //the edit function
import save from "./save.js"; //the save function

registerBlockType("udemy-plus/team-member", {
  icon: {
    src: icons.primary,
  },
  edit,
  save,
});
