/**
 * BLOCK: cool timeline instant timeline builder
 *
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { RichText,  } = wp.blockEditor;
import { CoolTMIcon } from '../components/icons/plus';
import { blockProps, ContainerSave } from '../components/container/container';
import Edit from './edit';
//  Import CSS.
import './style.scss';
import './editor.scss';

/**
 * Provides the initial data for new block
 */
export const defaultItem = {
    title: __( '', 'cool-timeline' ),
    description: __( '', 'cool-timeline' ),
    time:'',
    storyImage:'none',
    storyBgColor:'#fff',
    alt:'',
    storyTextColor:'#000',
    order:0
};

export const defaultSubBlocks = JSON.stringify( [
    {
        title: __( 'Cool Timeline Pro Launched', 'cool-timeline' ),
        description: __( 'Cool Timeline Pro is WordPress #1 premium timeline plugin, trusted by 20000+ users.', 'cool-timeline' ),
        time: __( '14 July, 2016', 'cool-timeline' ),
        key: new Date().getTime() + 1,
        storyImage:'none',
        storyBgColor:'#fff',
        storyTextColor:'#000',
        alt:'',
        order:1,
        position:0,
    },
    {
        title: __( 'Add Story Title Here', 'cool-timeline' ),
        description: __( 'Here you can add small description about your company history or achievements.', 'cool-timeline' ),
        time: __( '01 June, 2019', 'cool-timeline' ),
        key: new Date().getTime() + 1,
        storyImage:'none',
        storyBgColor:'#fff',
        alt:'',
        storyTextColor:'#000',
        order:2,
        position:1
    }
] );

/**
 * Generate inline styles for custom settings of the block
 * @param {Object} attributes - of the block
 * @returns {Node} generated styles
 */
export const getStyles = attributes => {
    const wrapperStyles = {
        maxWidth: `${ attributes.containerMaxWidth === '100%' ? '100%' : attributes.containerMaxWidth + 'px' }`,
        '--maxWidth': `${ attributes.containerMaxWidth === '100%' ? '100wh' : attributes.containerMaxWidth + ' ' } `,
    };

    const otherStyles = {
        '--timeLineColor': attributes.timeLineColor,
        '--textColor': attributes.textColor,
        '--titleSize': attributes.titleSize,
        '--descriptionSize': attributes.descriptionSize,
        '--timeSize': attributes.timeSize,
    };

    return {
        otherStyles,
        wrapperStyles,
    };
};

/**
 * Register: a Gutenberg Block.
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'ctl/instant-timeline', {
    title: __( 'Cool Timeline - Instant Builder ', 'cool-timeline' ),
    icon: CoolTMIcon,
    category: 'layout',
    keywords: [
        __( 'timeline', 'cool-timeline','instant timeline','events timeline' ),
    ],
    anchor: true,
    html: true,
    supports: {
		inserter: false,	
	},
    attributes: {
        ...blockProps,
        timelineLayout:{
            type:'string',
            default:'both-sided'
        },
        titleSize: {
            type: 'number',
            default: 18,
        },
        descriptionSize: {
            type: 'number',
            default: 14,
        },
        timeSize: {
            type: 'number',
            default: 18,
        },
        textColor: {
            type: 'string',
            default: '#000',
        },
        timeLineColor: {
            type: 'string',
            default: '#D91B3E',
        },
        items: {
            type: 'array',
            default: [],
        },

        isFirstLoad: {
            type: 'boolean',
            default: true,
        },

        blockUniqId: {
            type: 'number',
            default: 0,
        },
    },

    edit: ( props ) => {
        if ( props.attributes.items.length === 0 && props.attributes.isFirstLoad ) {
            props.setAttributes( {
                items: [ ...JSON.parse( defaultSubBlocks ) ],
                isFirstLoad: false,
            } );
            // TODO It is very bad solution to avoid low speed working of setAttributes function
            props.attributes.items = JSON.parse( defaultSubBlocks );
            if ( ! props.attributes.blockUniqId ) {
                props.setAttributes( {
                    blockUniqId: new Date().getTime(),
                } );
            }
        }

        return ( <Edit { ...props } /> );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     * @param {Object} props - attributes
     * @returns {Node} rendered component
     */
    save: function( props ) {
        const {
            className,
            attributes,
        } = props;

        const { otherStyles, wrapperStyles } = getStyles( props.attributes );

        const storyTime = ( item ) => (
            <div className="ctl-col-6">

                <div className="story-time">
                    <RichText.Content
                        tagName="div"
                        value={ item.time }
                        style={ {
                            color: attributes.textColor,
                            fontSize: `${ attributes.timeSize }px`,
                        } }
                    />
                </div>
            </div>
        );

        const storyDetails = ( item ) => (
            <div className="ctl-col-6">
                <div className="story-details">
                <div class="story-image">
                    {(item.storyImage!=='none') &&
                        <img src={item.storyImage} alt={item.alt}/>
                    }
                </div>
                    <RichText.Content
                        tagName="h3"
                        value={ item.title }
                        style={ {
                            color: attributes.textColor,
                            fontSize: `${ attributes.titleSize }px`,
                            lineHeight: `${ attributes.titleSize * 1.34 }px`,
                        } }
                    />
                    <RichText.Content
                        tagName="p"
                        value={ item.description }
                        style={ {
                            color: attributes.textColor,
                            fontSize: `${ attributes.descriptionSize }px`,
                            lineHeight: `${ attributes.descriptionSize * 1.73 }px`,
                        } }
                    />
                </div>
            </div>
        ); 

        return (
            <div className={ className ? className : '' }>
                <ContainerSave
                    className={ `ctl-instant-timeline block-${ attributes.blockUniqId }` }
                    attributes={ attributes }
                    style={ otherStyles }  
                   >
                            { attributes.items && attributes.items.map( ( item, index ) => (
                                <div
                                    key={ item.key }
                                    className="timeline-content"
                                >
                                    { ( index + 1 ) % 2 !== 0 ? (
                                        <div className="ctl-row">
                                            { storyTime( item ) }
                                            { storyDetails( item ) }
                                        </div>
                                    ) : (
                                        <div className="ctl-row">
                                            { storyDetails( item ) }
                                            { storyTime( item ) }
                                        </div>
                                    ) }
                                </div>
                            ) ) }
                </ContainerSave>
            </div>
        );
    },
} );
