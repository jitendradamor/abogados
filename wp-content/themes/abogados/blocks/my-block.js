const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls, MediaUpload } = wp.blockEditor;
const { PanelBody, ColorPalette, RangeControl, Button, TextControl } = wp.components;
const { useState } = wp.element;

registerBlockType('my-theme/my-block', {
    title: 'Hero Banner',
    icon: 'smiley',
    category: 'common',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
        },
        smallTitle: {
            type: 'string',
            default: 'Small Title'
        },
        textColor: {
            type: 'string',
            default: 'black'
        },
        fontSize: {
            type: 'number',
            default: 16
        },
        backgroundColor: {
            type: 'string',
            default: 'white'
        },
        backgroundImage: {
            type: 'string',
            default: null
        },
        title: {
            type: 'string',
            default: 'My Block Title'
        },
        description: {
            type: 'string',
            default: 'This is the block description'
        },
        searchPlaceholder: {
            type: 'string',
            default: 'Search...'
        },
        afterSearchText: {
            type: 'string',
            default: 'This is text after the search box.'
        },
        showSearchBox: {
            type: 'boolean',
            default: true
        }
    },
    edit: function (props) {
        const { attributes: { content, smallTitle, textColor, fontSize, backgroundColor, backgroundImage, title, description, searchPlaceholder, afterSearchText, searchQuery, showSearchBox }, setAttributes } = props;

        const [searchResultText, setSearchResultText] = useState(''); // New state for search result text

        const onSelectImage = (media) => {
            setAttributes({ backgroundImage: media.url });
        };

        const handleSearch = (e) => {
            e.preventDefault(); // Prevent form submission
            setSearchResultText(`You searched for: ${searchQuery}`); // Update search result text
        };

        return [
            wp.element.createElement(InspectorControls, null,
                wp.element.createElement(PanelBody, { title: 'Text Settings', initialOpen: true },
                    wp.element.createElement(ColorPalette, {
                        value: textColor,
                        onChange: (newColor) => setAttributes({ textColor: newColor }),
                    }),
                    wp.element.createElement(RangeControl, {
                        label: 'Font Size',
                        value: fontSize,
                        onChange: (newSize) => setAttributes({ fontSize: newSize }),
                        min: 8,
                        max: 100,
                    }),
                    wp.element.createElement(PanelBody, { title: 'Background Color', initialOpen: true },
                        wp.element.createElement(ColorPalette, {
                            value: backgroundColor,
                            onChange: (newColor) => setAttributes({ backgroundColor: newColor }),
                        })
                    ),
                    wp.element.createElement(PanelBody, { title: 'Background Image', initialOpen: true },
                        wp.element.createElement(MediaUpload, {
                            onSelect: onSelectImage,
                            allowedTypes: ['image'],
                            render: (obj) => wp.element.createElement(Button, {
                                className: 'components-button button button-large',
                                onClick: obj.open
                            }, 'Choose Background Image')
                        })
                    ),
                    wp.element.createElement(TextControl, {
                        label: 'Small Title',
                        value: smallTitle,
                        onChange: (value) => setAttributes({ smallTitle: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Title',
                        value: title,
                        onChange: (value) => setAttributes({ title: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Description',
                        value: description,
                        onChange: (value) => setAttributes({ description: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'Search Placeholder',
                        value: searchPlaceholder,
                        onChange: (value) => setAttributes({ searchPlaceholder: value })
                    }),
                    wp.element.createElement(TextControl, {
                        label: 'After Search Text',
                        value: afterSearchText,
                        onChange: (value) => setAttributes({ afterSearchText: value })
                    })
                )
            ),
            wp.element.createElement('div', {
                className: 'hero-banner-block',
                style: {
                    backgroundImage: `url(${backgroundImage})`,
                    backgroundColor: backgroundColor,
                    textAlign: 'center'
                }
            },
                wp.element.createElement('h4', { style: { color: textColor } }, smallTitle),
                wp.element.createElement('h2', { style: { color: textColor } }, title),
                wp.element.createElement('p', { style: { color: textColor } }, description),
                wp.element.createElement('div', {
                    className: 'hero-form'
                },
                    showSearchBox && wp.element.createElement('form', { onSubmit: handleSearch },
                        wp.element.createElement('input', {
                            type: 'text',
                            placeholder: searchPlaceholder,
                            value: searchQuery,
                            onChange: (e) => setAttributes({ searchQuery: e.target.value }),
                            required: true // Required field added
                        }),
                        wp.element.createElement('button', {
                            type: 'submit',
                            className: 'search-icon'
                          },
                            wp.element.createElement('svg', { 
                                width: "18", 
                                height: "18", 
                                viewBox: "0 0 18 18", 
                                fill: "none", 
                                xmlns: "http://www.w3.org/2000/svg"
                            },
                              wp.element.createElement('path', { 
                                  d: "M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.14583 12.3708 1.8875 11.1125C0.629167 9.85417 0 8.31667 0 6.5C0 4.68333 0.629167 3.14583 1.8875 1.8875C3.14583 0.629167 4.68333 0 6.5 0C8.31667 0 9.85417 0.629167 11.1125 1.8875C12.3708 3.14583 13 4.68333 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.8125 10.5625 9.6875 9.6875C10.5625 8.8125 11 7.75 11 6.5C11 5.25 10.5625 4.1875 9.6875 3.3125C8.8125 2.4375 7.75 2 6.5 2C5.25 2 4.1875 2.4375 3.3125 3.3125C2.4375 4.1875 2 5.25 2 6.5C2 7.75 2.4375 8.8125 3.3125 9.6875C4.1875 10.5625 5.25 11 6.5 11Z", 
                                  fill: "#1C1B1F" 
                              })
                            )
                        )
                    ),
                    searchResultText && wp.element.createElement('p', { style: { color: textColor } }, searchResultText) // Display the search result text
                ),
                wp.element.createElement('p', { style: { color: textColor } }, afterSearchText),
            )
        ];
    },
    save: function (props) {
        const { content, smallTitle, textColor, fontSize, backgroundColor, backgroundImage, title, description, searchPlaceholder, afterSearchText, searchQuery, showSearchBox } = props.attributes;
      
        return wp.element.createElement('div', {
            className: 'hero-banner-block',
            style: {
                backgroundImage: `url(${backgroundImage})`,
                backgroundColor: backgroundColor,
                textAlign: 'center'
            }
        },
        wp.element.createElement('div', { className: 'container' },
            wp.element.createElement('p', { className: 'small-text', style: { color: textColor } }, smallTitle),
            wp.element.createElement('h1', { style: { color: textColor } }, title),
            wp.element.createElement('p', { className: 'title-desc', style: { color: textColor } }, description),
            wp.element.createElement('div', {
                className: 'hero-form'
            },
                showSearchBox && wp.element.createElement('form', {
                    action: '/abogados',
                    method: 'GET',
                },
                    wp.element.createElement('input', {
                        type: 'text',
                        placeholder: searchPlaceholder,
                        name: 's',
                        defaultValue: searchQuery,
                        required: true // Required field added
                    }),
                    wp.element.createElement('button', {
                        type: 'submit',
                        className: 'search-icon'
                      },
                        wp.element.createElement('svg', { 
                            width: "18", 
                            height: "18", 
                            viewBox: "0 0 18 18", 
                            fill: "none", 
                            xmlns: "http://www.w3.org/2000/svg"
                        },
                          wp.element.createElement('path', { 
                              d: "M16.6 18L10.3 11.7C9.8 12.1 9.225 12.4167 8.575 12.65C7.925 12.8833 7.23333 13 6.5 13C4.68333 13 3.14583 12.3708 1.8875 11.1125C0.629167 9.85417 0 8.31667 0 6.5C0 4.68333 0.629167 3.14583 1.8875 1.8875C3.14583 0.629167 4.68333 0 6.5 0C8.31667 0 9.85417 0.629167 11.1125 1.8875C12.3708 3.14583 13 4.68333 13 6.5C13 7.23333 12.8833 7.925 12.65 8.575C12.4167 9.225 12.1 9.8 11.7 10.3L18 16.6L16.6 18ZM6.5 11C7.75 11 8.8125 10.5625 9.6875 9.6875C10.5625 8.8125 11 7.75 11 6.5C11 5.25 10.5625 4.1875 9.6875 3.3125C8.8125 2.4375 7.75 2 6.5 2C5.25 2 4.1875 2.4375 3.3125 3.3125C2.4375 4.1875 2 5.25 2 6.5C2 7.75 2.4375 8.8125 3.3125 9.6875C4.1875 10.5625 5.25 11 6.5 11Z", 
                              fill: "#1C1B1F" 
                          })
                        )
                    )
                )
            ),
            wp.element.createElement('p', { className: 'bottom-text', style: { color: textColor } }, afterSearchText),
        ));
    }
});
