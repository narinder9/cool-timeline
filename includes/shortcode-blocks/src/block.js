/**
 * Block dependencies
 */

import CtlIcon from './icons';
import CtlLayoutType from './layout-type'
import PreviewImage from './images/timeline.png'

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const baseURL=ctlUrl;
const LayoutImgPath=baseURL+'/includes/shortcode-blocks/layout-images';
const { apiFetch } = wp;
const {
	RichText,
	InspectorControls,
	BlockControls,
} = wp.editor;
const { Fragment } = wp.element
const { 
	PanelBody,
	PanelRow,
	TextareaControl,
	TextControl,
	Dashicon,
	Toolbar,
	ButtonGroup,
	Button,
	SelectControl,
	Tooltip,
	RangeControl,
	TabPanel,
	Card
} = wp.components;


/**
 * Register block

 */
export default registerBlockType( 'cool-timleine/shortcode-block', {
		// Block Title
		title: __( 'Cool Timeline Shortcode' ),
		// Block Description
		description: __( 'Cool Timeline Shortcode Generator.' ),
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
		},
		isPreview:{
			type: 'boolean',
			default: false
		}
	},
	// Defining the edit interface
	edit: props => {
		const skinOptions = [
            { value: 'default', label: __( 'Default' ) },
			{ value: 'clean', label: __( 'Clean' ) }
		];
		// const iconOptions = [
        //     { value: 'NO', label: __( 'NO' ) },
        //     { value: 'YES', label: __( 'YES' ) }
		// ];
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
            { value: 'fade-up', label: __( 'fadeInUp' ) }
		];
		const contentSettings=[{label:"Summary",value:"short"},
			{label:"Full Text",value:"full"}
			];
		const general_settings=
		<PanelBody title={ __( 'Timeline General Settings' ) } >
					<SelectControl
                        label={ __( 'Select Layout' ) }
                        description={ __( 'Vertical/Horizontal' ) }
                        options={ layoutOptions }
                        value={ props.attributes.layout }
						onChange={ ( value ) =>props.setAttributes( { layout: value } ) }
						__nextHasNoMarginBottom={true}
						/>
						<p>Select your timeline Layout.</p>
						{props.attributes.layout != "horizontal" && 
						<Fragment>
						<div className="ctl_shortcode-button-group_label">{__("Skin")}</div>
						<ButtonGroup className="ctl_shortcode-button-group">
							<Button onClick={(e) => {props.setAttributes({skin: 'default'})}} className={props.attributes.skin == 'default' ? 'active': ''} isSmall={true}>Default</Button>
							<Button onClick={(e) => {props.setAttributes({skin: 'clean'})}} className={props.attributes.skin == 'clean' ? 'active': ''} isSmall={true}>Clean</Button>
						</ButtonGroup>
						{/* <SelectControl
                        label={ __( 'Skin' ) }
                        options={ skinOptions }
                        value={ props.attributes.skin }
						onChange={ ( value ) =>props.setAttributes( { skin: value } ) }
                    	/> */}
						<p>Create Light, Dark or Colorful Timeline</p>
						</Fragment>
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
						<RangeControl
							label={props.attributes.layout!="horizontal"? __( 'Display Pers Page?' ) : __( 'Slide To Show?' )}
							value={ parseInt(props.attributes.postperpage) }
							onChange={ ( value ) => props.setAttributes( { postperpage: value.toString() } ) }
							min={ 1 }
							max={ props.attributes.layout=="horizontal"?10:50}
							step={ 1 }
						/>	
						<p>{props.attributes.layout!="horizontal"?
						 __( 'You Can Show Pagination After These Posts In Vertical Timeline.' ) 
						 : __( 'Set slide per view.' )}</p>
						{/* { props.attributes.layout!="horizontal" &&  
						<Fragment>
						<TextControl
							label={ __( 'Display Pers Page?' ) }
							value={ props.attributes.postperpage }
							onChange={ ( value ) =>props.setAttributes( { postperpage: value } ) }
						/>
						<p>You Can Show Pagination After These Posts In Vertical Timeline. </p>
						</Fragment>
						}
						{ props.attributes.layout=="horizontal" && 
						<Fragment>
						<TextControl
							label={ __( 'Slide To Show?' ) }
							value={ props.attributes.postperpage }
							onChange={ ( value ) =>props.setAttributes( { postperpage: value } ) }
						/>
						<p>Set slide per view.</p>
						</Fragment>
					} */}
					
					</PanelBody>;
		const advanced_settings=
		<PanelBody title={ __( 'Timeline Advanced Settings' ) } >
			<div className="ctl_shortcode-button-group_label">{__("Stories Order?")}</div>
			<ButtonGroup className="ctl_shortcode-button-group">
				<Button onClick={(e) => {props.setAttributes({order: 'DESC'})}} className={props.attributes.order == 'DESC' ? 'active': ''} isSmall={true}>DESC</Button>
				<Button onClick={(e) => {props.setAttributes({order: 'ASC'})}} className={props.attributes.order == 'ASC' ? 'active': ''} isSmall={true}>ASC</Button>
			</ButtonGroup>
			  {/* <SelectControl
                label={ __( 'Stories Order?' ) }
				options={ [{label:"DESC",value:"DESC"},
						{label:"ASC",value:"ASC"}
					] }
                value={ props.attributes.order }
				onChange={ ( value ) =>props.setAttributes( { order: value } ) }
				/>	 */}
			<p>Timeline Stories order like:- DESC(2017-1900) , ASC(1900-2017)</p>
			<SelectControl
                label={ __( 'Date Formats' ) }
                description={ __( 'yes/no' ) }
                options={ DfromatOptions }
                value={ props.attributes.dateformat }
				onChange={ ( value ) =>props.setAttributes( { dateformat: value } ) }
            />
			<p>Timeline Stories dates formats</p>
			{/* <SelectControl
                label={ __( 'Icons' ) }
                description={ __( 'yes/no' ) }
                options={ iconOptions }
                value={ props.attributes.icons }
				onChange={ ( value ) => props.setAttributes( { icons: value } ) }
            	/> */}
			<div className="ctl_shortcode-button-group_label">{__("Icons")}</div>
			<ButtonGroup className="ctl_shortcode-button-group">
				<Button onClick={(e) => {props.setAttributes({icons: 'NO'})}} className={props.attributes.icons == 'NO' ? 'active': ''} isSmall={true}>No</Button>
				<Button onClick={(e) => {props.setAttributes({icons: 'YES'})}} className={props.attributes.icons == 'YES' ? 'active': ''} isSmall={true}>Yes</Button>
			</ButtonGroup>
			<p>Display Icons In Timeline Stories. By default Is Dot.</p>
				
			{ props.attributes.layout!="horizontal" &&  
				<SelectControl
                label={ __( 'Animation' ) }
                description={ __( 'yes/no' ) }
                options={ animationOptions }
                value={ props.attributes.animation }
				onChange={ ( value ) =>props.setAttributes( { animation: value } ) }
            	/>
			}
		</PanelBody>;
		return [
			
			!! props.isSelected && (
				<InspectorControls key="inspector">
					<TabPanel
					className="ctl_shortcode-tab-settings"
					activeClass="active-tab"
					tabs={ [
						{
							name: 'general_settings',
							title: 'General',
							className: 'ctl-settings_tab-one',
							content: general_settings
						},
						{
							name: 'advanced_settings',
							title: 'Advanced',
							className: 'ctl-settings_tab-two',
							content: advanced_settings
						},
					] }
					>
						{ ( tab ) => <Card>{tab.content}</Card> }
					</TabPanel>
				</InspectorControls>
			),
			props.attributes.isPreview ? <img src={PreviewImage}></img> :
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
	example: {
		attributes: {
			isPreview: true,
		},
	},
});
