"use strict"

const el = wp.element.createElement;
const withState = wp.compose.withState;
const withSelect = wp.data.withSelect;
const withDispatch = wp.data.withDispatch;

wp.hooks.addFilter(
    'editor.PostFeaturedImage',
    'ts-fic/featured-image-caption',
    wrapPostFeaturedImage
);

function wrapPostFeaturedImage(OriginalComponent) {
    return function(props) {
        return (
            el(
                wp.element.Fragment, {},
                '',
                el(
                    OriginalComponent,
                    props
                ),
                el(
                    composedText
                )
            )
        );
    }
}

class TextCustom extends React.Component {
    render() {
        const {
            meta,
            updateFeaturedImageCaption,
        } = this.props;

        return (
            el(
                wp.components.TextControl, {
                    className: "wrap",
                    label: "Image caption",
                    help: "Image caption to show below the featured image.",
                    value: meta.ts_fic_featured_image_caption,
                    onChange: (value) => {
                        updateFeaturedImageCaption(value, meta);
                    }
                }
            )
        )
    }
}

const composedText = wp.compose.compose([
    withSelect((select) => {
        const currentMeta = select('core/editor').getCurrentPostAttribute('meta');
        const editedMeta = select('core/editor').getEditedPostAttribute('meta');
        return {
            meta: {...currentMeta, ...editedMeta },
        };
    }),
    withDispatch((dispatch) => ({
        updateFeaturedImageCaption(value, meta) {
            meta = {
                ...meta,
                ts_fic_featured_image_caption: value,
            };
            dispatch('core/editor').editPost({ meta });
        },
    })),
])(TextCustom);