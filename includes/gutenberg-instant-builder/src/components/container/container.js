const { __ } = wp.i18n; // Import __() from wp.i18n
const { RangeControl, CheckboxControl, SelectControl, RadioControl, PanelBody, Button } = wp.components;
const { Component, Fragment } = wp.element;
const { MediaUpload, PanelColorSettings } = wp.editor;

export const blockProps = {
    containerMaxWidth: {
        type: 'number',
        default: 1170,
    },
    backgroundColor: {
        type: 'string',
        default: '#FFF',
    },
    backgroundImage: {
        type: 'string',
        default: 'none',
    },
    backgroundImageId: {
        type: 'string',
        default: '',
    },
    backgroundStyle: {
        type: 'string',
        default: '',
    },  
};

/**
 * Implements inspector container
 */
export class InspectorContainer extends Component {
    render() {
        const {
            containerMaxWidth,
            setAttributes,
        } = this.props;
        return (
            <Fragment>
            
              
            </Fragment>
        );
    }
}


/**
 * Implements the edit container
 * @param {Object} props from editor
 * @return {Node} rendered edit component
 * @constructor
 */
export const ContainerEdit = ( props ) => {
    const styles = {};
  /*  let timelineSizeCls = 'ctl-lg';
    if ( props.attributes.timelineLayout=='both-sided' ) {
        timelineSizeCls = 'ctl-lg';
    }
    if ( props.attributes.timelineLayout=='one-sided') {
        timelineSizeCls = 'ctl-sm';
    } */
 
    return (
        <div
            className={ `${ props.className } ${ props.attributes.timelineLayout } ` }
            style={ { ...styles, ...props.style } }
        >
            { props.children }
        </div>
    );
};

/**
 * Implements the save container
 * @param {Object} props from editor
 * @return {Node} rendered edit component
 * @constructor
 */
export const ContainerSave = ( props ) => {
    const styles = {};   
   /*
    let timelineSizeCls = 'ctl-lg';
    if ( props.attributes.timelineLayout=='both-sided' ) {
        timelineSizeCls = 'ctl-lg';
    }
    if ( props.attributes.timelineLayout=='one-sided') {
        timelineSizeCls = 'ctl-sm';
    }
    if ( props.attributes.width100 ) {
        timelineSizeCls = 'ctl-lg';
    } */
    return (
        <div
            className={ `${ props.className }  ${props.attributes.timelineLayout}` }
            style={ { ...styles, ...props.style } }
        >
            { props.children }
        </div>
    );
};
