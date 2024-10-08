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
            default: false
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
                    padding: '20px',
                    textAlign: 'center'
                }
            },
                wp.element.createElement('h4', { style: { color: textColor } }, smallTitle),
                wp.element.createElement('h2', { style: { color: textColor } }, title),
                wp.element.createElement('p', { style: { color: textColor } }, description),
                wp.element.createElement('div', {
                    style: { display: 'flex', alignItems: 'center', justifyContent: 'center', marginTop: '20px' }
                },
                    wp.element.createElement(Button, {
                        className: 'search-icon',
                        onClick: () => setAttributes({ showSearchBox: !showSearchBox }),
                        style: { marginRight: '10px' }
                    },
                        wp.element.createElement('span', { className: 'dashicons dashicons-search' }) // Search icon
                    ),
                    showSearchBox && wp.element.createElement('form', { onSubmit: handleSearch },
                        wp.element.createElement('input', {
                            type: 'text',
                            placeholder: searchPlaceholder,
                            value: searchQuery,
                            onChange: (e) => setAttributes({ searchQuery: e.target.value }), // Update search query
                            style: { padding: '10px', fontSize: '16px', width: '200px', marginRight: '10px' }
                        }),
                        wp.element.createElement(Button, {
                            isPrimary: true,
                            type: 'submit',
                            className: 'search-icon',
                            style: { padding: '10px' }
                        },
                            wp.element.createElement('span', { className: 'dashicons dashicons-search' }) // Icon for search button
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
                padding: '20px',
                textAlign: 'center'
            }
        },
        wp.element.createElement('div', { className: 'container' },
            wp.element.createElement('span', { style: { color: textColor } }, smallTitle),
            wp.element.createElement('h1', { style: { color: textColor } }, title),
            wp.element.createElement('p', { style: { color: textColor } }, description),
            wp.element.createElement('div', {
                style: { display: 'flex', alignItems: 'center', justifyContent: 'center', marginTop: '20px' }
            },
                wp.element.createElement('div', {
                    className: 'search-icon',
                    onClick: () => setAttributes({ showSearchBox: !showSearchBox }),
                    style: { cursor: 'pointer', marginRight: '10px' }
                },
                    wp.element.createElement('span', { className: 'dashicons dashicons-search' }) // Search icon
                ),
                showSearchBox && wp.element.createElement('form', {
                    action: '/abogados',
                    method: 'GET',
                },
                    wp.element.createElement('input', {
                        type: 'text',
                        placeholder: searchPlaceholder,
                        name: 's',
                        defaultValue: searchQuery,
                        style: { padding: '10px', fontSize: '16px', width: '200px', marginRight: '10px' }
                    }),
                    wp.element.createElement('button', {
                        type: 'submit',
                        className: 'search-icon',
                        style: { padding: '10px' }
                    },
                        wp.element.createElement('span', { className: 'dashicons dashicons-search' }) // Icon for search button
                    )
                )
            ),
            wp.element.createElement('p', { style: { color: textColor } }, afterSearchText),
        ));
    }
});
