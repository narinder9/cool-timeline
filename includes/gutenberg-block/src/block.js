/**
 * Block dependencies
 */

import classnames from 'classnames';
import CtlIcon from './icons';
import CtlLayoutType from './layout-type'

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const baseURL=ctlUrl;
const LayoutImgPath=baseURL+'/includes/gutenberg-block/layout-images';
const { apiFetch } = wp;
const {
	RichText,
	InspectorControls,
	BlockControls,
} = wp.editor;

const { 
	PanelBody,
	PanelRow,
	TextareaControl,
	TextControl,
	Dashicon,
	Toolbar,
	Button,
	SelectControl,
	Tooltip,
	RangeControl,
} = wp.components;


/**
 * Register block

 */
export default registerBlockType( 'cool-timleine/shortcode-block', {
		// Block Title
		title: __( 'Cool Timeline Shortcode' ),
		// Block Description
		description: __( 'Cool Timeline Shortcode Generator Block.' ),
		// Block Category
		category: 'layout',
		// Block Icon
		icon:CtlIcon,
		// Block Keywords
		keywords: [
			__( 'cool timeline' ),
			__( 'timeline shortcode' ),
			__( 'cool timeline block' )
		],
	attributes: {
		layout: {
			type: 'string',
			default: 'default'
		},
		skin: {
			type: 'string',
			default: 'default'
		},
		postperpage: {
            type: 'string',
            default:10
        },
		dateformat: {
			type: 'string',
			default:  'F j',
		},
		icons: {
			type: 'string',
			default:  'NO',
		},
		animation: {
			type: 'string',
			default:  'none',
		},
		storycontent:{
			type: 'string',
			default:  'short'
		},
		order:{
			type: 'string',
			default:'DESC'
		}
	},
	// Defining the edit interface
	edit: props => {
		const skinOptions = [
            { value: 'default', label: __( 'Default' ) },
			{ value: 'clean', label: __( 'Clean' ) }
		];
		const iconOptions = [
            { value: 'NO', label: __( 'NO' ) },
            { value: 'YES', label: __( 'YES' ) }
		];
		const DfromatOptions = [
		 {value:"F j",label:"F j"},
		 {value:"F j Y",label:"F j Y"},
		 {value:"Y-m-d",label:"Y-m-d"},
		 {value:"m/d/Y",label:"m/d/Y"},
		 {value:"d/m/Y",label:"d/m/Y"},
		 {value:"F j Y g:i A",label:"F j Y g:i A"},
		 {value:"Y",label:"Y"}
  		];
		const layoutOptions = [
            { value: 'default', label: __( 'Vertical' ) },
			{ value: 'horizontal', label: __( 'Horizontal' ) },
			{ value: 'one-side', label: __( 'One Side Layout' ) },
			{ value: 'simple', label: __( 'Simple Layout' ) },
			{ value: 'compact', label: __( 'Compact Layout' ) }
		];
		const animationOptions = [
            { value: 'none', label: __( 'None' ) },
            { value: 'fadeInUp', label: __( 'fadeInUp' ) }
		];
		const contentSettings=[{label:"Summary",value:"short"},
			{label:"Full Text",value:"full"}
			];
		return [
			
			!! props.isSelected && (
				<InspectorControls key="inspector">
						<PanelBody title={ __( 'Timeline Settings' ) } >
					<SelectControl
                        label={ __( 'Select Layout' ) }
                        description={ __( 'Vertical/Horizontal' ) }
                        options={ layoutOptions }
                        value={ props.attributes.layout }
						onChange={ ( value ) =>props.setAttributes( { layout: value } ) }
						/>
						<p>Select your timeline Layout.</p>
						{ props.attributes.layout!="horizontal" &&  
						<section>
						<TextControl
							label={ __( 'Display Pers Page?' ) }
							value={ props.attributes.postperpage }
							onChange={ ( value ) =>props.setAttributes( { postperpage: value } ) }
						/>
						<p>You Can Show Pagination After These Posts In Vertical Timeline. </p>
						</section>
						}
						{ props.attributes.layout=="horizontal" && 
						<section>
							<TextControl
							label={ __( 'Slide To Show?' ) }
							value={ props.attributes.postperpage }
							onChange={ ( value ) =>props.setAttributes( { postperpage: value } ) }
						/>
						<p>Set slide per view.</p>
						</section>
					}

					<SelectControl
                        label={ __( 'Date Formats' ) }
                        description={ __( 'yes/no' ) }
                        options={ DfromatOptions }
                        value={ props.attributes.dateformat }
						onChange={ ( value ) =>props.setAttributes( { dateformat: value } ) }
                    	/>
					<p>Timeline Stories dates formats</p>
					{ props.attributes.layout!="horizontal" &&  
							  <section>
						<SelectControl
                        label={ __( 'Skin' ) }
                        options={ skinOptions }
                        value={ props.attributes.skin }
						onChange={ ( value ) =>props.setAttributes( { skin: value } ) }
                    	/>
						<p>Create Light, Dark or Colorful Timeline</p>
						<SelectControl
                        label={ __( 'Icons' ) }
                        description={ __( 'yes/no' ) }
                        options={ iconOptions }
                        value={ props.attributes.icons }
						onChange={ ( value ) => props.setAttributes( { icons: value } ) }
                    	/>
						<p>Display Icons In Timeline Stories. By default Is Dot.</p>
						<SelectControl
                        label={ __( 'Animation' ) }
                        description={ __( 'yes/no' ) }
                        options={ animationOptions }
                        value={ props.attributes.animation }
						onChange={ ( value ) =>props.setAttributes( { animation: value } ) }
                    	/>
						</section>
					}
					 <SelectControl
                        label={ __( 'Stories Description?' ) }
                        options={ contentSettings }
                        value={ props.attributes.storycontent }
						onChange={ ( value ) =>props.setAttributes( { storycontent: value } ) }
						/>	
						<p>Timeline Story content type:<ul>
							<li><strong>Summary</strong>:- Short description</li>	
							<li><strong>Full</strong>:- All content with formated text.</li>
						</ul>
						</p>
					  <SelectControl
                        label={ __( 'Stories Order?' ) }
						options={ [{label:"DESC",value:"DESC"},
								{label:"ASC",value:"ASC"}
							] }
                        value={ props.attributes.order }
						onChange={ ( value ) =>props.setAttributes( { order: value } ) }
						/>	
						<p>Timeline Stories order like:- DESC(2017-1900) , ASC(1900-2017)</p>
					</PanelBody>
				</InspectorControls>
			),
			<div className={ props.className }>			
				<CtlLayoutType  LayoutImgPath={LayoutImgPath} attributes={props.attributes} />
				<div className="ctl-block-shortcode">[cool-timeline 
							layout="{props.attributes.layout}" 
							skin="{props.attributes.skin}"
							show-posts="{props.attributes.postperpage}"
							date-format="{ props.attributes.dateformat}"
							icons="{props.attributes.icons}"
							animation="{ props.attributes.animation}"
							story-content="{props.attributes.storycontent}"
							order="{props.attributes.order}"
						  ]
				</div>
			</div>
		];
	},
	// Defining the front-end interface
	save() {
		// Rendering in PHP
		return null;
	},
});
