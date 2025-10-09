import { registerBlockType } from "@wordpress/blocks";
import {
  RichText,
  useBlockProps,
  InspectorControls,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { PanelBody, ColorPalette } from "@wordpress/components";
import block from "./block.json";
import icons from "../../icons.js";
import "./main.css";

registerBlockType(block.name, {
  icon: icons.primary,
  edit({ attributes, setAttributes }) {
    const { content, underlined_color } = attributes;
    const blockProps = useBlockProps();

    return (
      <>
        <InspectorControls>
          <PanelBody title={__("Colors", "udemy-plus")}>
            <ColorPalette
              colors={[
                { name: "Red", color: "#f87171" },
                { name: "Indigo", color: "#818cf8" },
              ]}
              value={underlined_color}
              onChange={(newValue) =>
                setAttributes({ underlined_color: newValue })
              }
            />
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          <RichText
            className="fancy-header"
            tagName="h2"
            placeholder={__("Enter Heading", "udemy-plus")}
            value={content}
            onChange={(newValue) => setAttributes({ content: newValue })}
            allowedFormats={["core/bold", "core/italic"]}
          />
        </div>
      </>
    );
  },
  save({ attributes }) {
    const { content, underlined_color } = attributes;
    const blockProps = useBlockProps.save({
      className: "fancy-header",
      style: {
        "background-image": `
        linear-gradient(transparent, transparent), linear-gradient(${underlined_color}, ${underlined_color});
        `,
      },
    });

    return <RichText.Content {...blockProps} tagName="h2" value={content} />;
  },
});
