import {
  useBlockProps,
  InspectorControls,
  RichText,
  MediaPlaceholder,
  BlockControls,
  MediaReplaceFlow,
} from "@wordpress/block-editor"; //for the block editor
import { __ } from "@wordpress/i18n";
import {
  PanelBody,
  TextareaControl,
  Spinner,
  ToolbarButton,
  Tooltip,
  Icon,
  TextControl,
  Button,
} from "@wordpress/components"; //for the inspector controls
import { isBlobURL, revokeBlobURL } from "@wordpress/blob"; //to check if the image is a temporary blob URL
import { useState } from "@wordpress/element"; // React's useState hook

// Load block configuration from block.json
export default function ({ attributes, setAttributes, context, isSelected }) {
  const { name, title, bio, imgID, imgAlt, imgURL, socialHandles } = attributes;
  const blockProps = useBlockProps();

  const [imgPreview, setImgPreview] = useState(imgURL);

  const selectImage = (img) => {
    //console.log(img);
    let newImgURL = null;

    if (isBlobURL(img.url)) {
      //if the image is a temporary blob URL, use the teamMember size URL instead
      newImgURL = img.url;
    } else {
      newImgURL = img.sizes
        ? img.sizes.teamMember.url
        : img.media_details.sizes.teamMember.source_url;

      setAttributes({
        imgID: img.id,
        imgAlt: img.alt,
        imgURL: newImgURL,
      });

      //revoke the temporary blob URL to avoid memory leaks
      revokeBlobURL(imgPreview);
    }

    setImgPreview(newImgURL);
  };

  const selectImgURL = (url) => {
    setAttributes({
      imgID: null,
      imgAlt: null,
      imgURL: url,
    });

    setImgPreview(url);
  };

  const imageClass = `wp-image-${imgID} img-${context["udemy-plus/image-shape"]}`;

  const [activeSocialLink, setActiveSocialLink] = useState(null);

  setAttributes({
    imageShape: context["udemy-plus/image-shape"],
  });

  return (
    <>
      {imgPreview && (
        <BlockControls group="inline">
          <MediaReplaceFlow
            name={__("Replace image", "udemy-plus")}
            mediaID={imgID}
            mediaURL={imgURL}
            acceptedTypes={["image"]} //png, jpg, jpeg, gif
            accept={"image/*"}
            onError={(error) => console.error(error)}
            onSelect={selectImage}
            onSelectURL={selectImgURL}
          />
          <ToolbarButton
            onClick={() => {
              setAttributes({
                imgID: 0,
                imgAlt: "",
                imgURL: "",
              });

              setImgPreview("");
            }}
          >
            {__("Remove image ", "udemy-plus")}
          </ToolbarButton>
        </BlockControls>
      )}
      <InspectorControls>
        <PanelBody title={__("Settings", "udemy-plus")}>
          {imgPreview && !isBlobURL(imgPreview) && (
            <TextareaControl
              label={__("Alt Attribute", "udemy-plus")}
              value={imgAlt}
              onChange={(imgAlt) => setAttributes({ imgAlt })}
              help={__(
                "Description of your image for screen readers.",
                "udemy-plus"
              )}
            />
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div className="author-meta">
          {imgPreview && (
            <img src={imgPreview} alt={imgAlt} className={imageClass} />
          )}
          {
            isBlobURL(imgPreview) && <Spinner /> //show spinner if the image is a temporary blob URL
          }

          <MediaPlaceholder
            acceptedTypes={["image"]} //png, jpg, jpeg, gif
            accept={"image/*"}
            icon="admin-users"
            onSelect={selectImage}
            onError={(error) => {
              console.error(error);
            }}
            disableMediaButtons={imgPreview} //disable if image is already selected
            onSelectURL={selectImgURL}
          />
          <p>
            <RichText
              placeholder={__("Name", "udemy-plus")}
              tagName="strong"
              onChange={(name) => setAttributes({ name })}
              value={name}
            />

            <RichText
              placeholder={__("Title", "udemy-plus")}
              tagName="span"
              onChange={(title) => setAttributes({ title })}
              value={title}
            />
          </p>
        </div>
        <div className="member-bio">
          <RichText
            placeholder={__("Member bio", "udemy-plus")}
            tagName="p"
            onChange={(bio) => setAttributes({ bio })}
            value={bio}
          />
        </div>
        <div className="social-links">
          {socialHandles.map((handle, index) => {
            return (
              <a
                href={handle.url}
                key={index}
                onClick={(event) => {
                  event.preventDefault();
                  setActiveSocialLink(
                    activeSocialLink === index ? null : index
                  );
                }}
                className={
                  activeSocialLink === index && isSelected ? "is-active" : ""
                }
              >
                <i className={`bi bi-${handle.icon}`}></i>
              </a>
            );
          })}
          {isSelected && (
            <Tooltip text={__("Add Social Media Handle", "udemy-plus")}>
              <a
                href="#"
                onClick={(event) => {
                  event.preventDefault();
                  setAttributes({
                    socialHandles: [
                      ...socialHandles,
                      { icon: "question", url: "" },
                    ],
                  });

                  setActiveSocialLink(socialHandles.length);
                }}
              >
                <Icon icon="plus" />
              </a>
            </Tooltip>
          )}
        </div>
        {isSelected && activeSocialLink !== null && (
          <div className="team-member-social-edit-ctr">
            <TextControl
              label={__("URL", "udemy-plus")}
              value={socialHandles[activeSocialLink].url}
              onChange={(url) => {
                const temporaryLink = { ...socialHandles[activeSocialLink] };
                const temporarySocial = [...socialHandles];
                temporaryLink.url = url;
                temporarySocial[activeSocialLink] = temporaryLink;

                setAttributes({ socialHandles: temporarySocial });
              }}
            />

            <TextControl
              label={__("Icon", "udemy-plus")}
              value={socialHandles[activeSocialLink].icon}
              onChange={(icon) => {
                const temporaryLink = { ...socialHandles[activeSocialLink] };
                const temporarySocial = [...socialHandles];
                temporaryLink.icon = icon;
                temporarySocial[activeSocialLink] = temporaryLink;

                setAttributes({ socialHandles: temporarySocial });
              }}
            />
            <Button
              isDestructive
              onClick={() => {
                const temporaryCopy = [...socialHandles];
                temporaryCopy.splice(activeSocialLink, 1);

                setAttributes({ socialHandles: temporaryCopy });
                setActiveSocialLink(null);
              }}
            >
              {__("Remove", "udemy-plus")}
            </Button>
          </div>
        )}
      </div>
    </>
  );
}
